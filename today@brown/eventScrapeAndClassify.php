<?php

require_once('../vendor/autoload.php');
require_once('../vendor/simple_html_dom/simple_html_dom.php');

//Make the that classifyer weights file can be opened.
$myfile = fopen("classifier_weights.txt", "r") or die("Unable to open file!");
$json_weights = fread($myfile,filesize("classifier_weights.txt"));
fclose($myfile);

echo 'Starting Scrape<br/>';
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);

//Open Stream to inbox:
$mbox = imap_open('{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX', 'jebc3s9fflbhhkez@gmail.com','FreeFoodForAll$$') or die('Cannot connect to Gmail: ' . imap_last_error());
if(!$mbox) { //Make sure it didn't fail
	die('failed open');
}

//Search emails
$emails = imap_search($mbox,'FROM "ScottH@brown.edu" ');

/* if emails are returned, cycle through each... */
if($emails) {
	rsort($emails);
	foreach($emails as $email_number) {
		/* get information specific to this email */
		$overview = imap_fetch_overview($mbox,$email_number,0);
		if(substr($overview[0]->subject, 0, 18) == "Fwd: Today@Brown -") { //Check whether its the right email.
			$body = imap_body($mbox,$email_number,0);
			$body = substr($body, (strpos($body, "Content-Type: text/html;") + 85));//add 85 to remove other stuff
			$html = str_get_html(quoted_printable_decode($body)); //decodes, and parses the html
			$event_links = array();
			foreach($html->find('a[href^="https://today.brown.edu/events/"]') as $a) {
				$event_links[] = substr($a->href, 0, 24).'api/'.substr($a->href, 24, (strpos($a->href, '?')-24)); //Put in the api key to get only the good info
			}
			$html->clear();
			unset($html);
			unset($body);

			//We only want to create the classifier once. 
			$classifier = new \Niiknow\Bayes(); //Create a classifier
			$classifier->fromJson($json_weightse); // load the classifier back from its JSON representation of weights.

			//Build associative arrays for each link. 
			$potential_events = array();
			foreach($event_links as $event_link) {
				$event_info = file_get_contents($event_link);
				//Check if failed
				if(!$event_info) {
					printf("Failed getting event");
					continue;
				}
				$infomation = json_decode($event_info);
				$evnt = array();
				$evnt['id'] = $infomation->id;
				$evnt['link'] = (isset($infomation->link) ? $infomation->link : "https://today.brown.edu/events/".$infomation->id);
				$evnt['location'] = $infomation->location;
				$evnt['description'] = $infomation->description;
				$evnt['title'] = $infomation->summary;
				$evnt['time_start'] = strtotime($infomation->start);
				$evnt['time_end'] = ($infomation->start == $infomation->end ? NULL : strtotime($infomation->end));

				/*
				THIS is the point in the file where the description and title portion of the event are put into the Naive Bayes classifyer, which determines whether it is an event with free food or not.  
				*/
				
				$free_food = $classifier->categorize($evnt['title'].$envt['description']);
				printf($free_food)
				if($free_food) {
					$potential_events[] = $evnt; //Add it to the list of free food events
				}
				unset($evnt);
			}
			
			unset($classifier);

			break; //Only want the most recent email, maybe change this for training and data collection. 
		}
	}
	//For the list of events, print them out:
	foreach($potential_events as $event) {
		printf($event['title']."<br/>");
	}
}
?>