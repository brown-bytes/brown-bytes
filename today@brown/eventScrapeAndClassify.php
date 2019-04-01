<?php

require_once('../vendor/autoload.php');
require_once('../vendor/simple_html_dom/simple_html_dom.php');

$config = parse_ini_file("/brownbytesconfig/config.ini");
$mysqli = new mysqli("localhost", $config["username"], $config["password"], "brown_bytes");


//Make the that classifyer weights file can be opened.
$myfile = fopen("../today@brown/classifier_weights.txt", "r") or die("Unable to open file!");
$json_weights = fread($myfile,filesize("../today@brown/classifier_weights.txt"));
fclose($myfile);

echo 'Starting Scrape<br/>';
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);

//Open Stream to inbox:
$mbox = imap_open('{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX', 'jebc3s9fflbhhkez@gmail.com','4bdude$$') or die('Cannot connect to Gmail: ' . imap_last_error());
if(!$mbox) { //Make sure it didn't fail
	die('failed open');
}

//Search emails
$emails = imap_search($mbox,'FROM "Today@brown.edu" ');
/* if emails are returned, cycle through each... */
if($emails) {
	rsort($emails);
	foreach($emails as $email_number) {
		/* get information specific to this email */
		$overview = imap_fetch_overview($mbox,$email_number,0);
		if(substr($overview[0]->subject, 0, 14) == "Today@Brown - " || substr($overview[0]->subject, 0, 35) == "=?UTF-8?Q?=F0=9F=94=B6_Today@Brown:") { //Check whether its the right email. There are two types, one where there are priority events, and otherwise. 
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
			unset($overview);

			//We only want to create the classifier once. 
			$classifier = new \Niiknow\Bayes(); //Create a classifier
			$classifier->fromJson($json_weights); // load the classifier back from its JSON representation of weights.

			//Build associative arrays for each link. 
			foreach($event_links as $event_link) {
				$event_info = file_get_contents($event_link);
				//Check if failed
				if(!$event_info) {
					printf("Failed getting event");
					continue;
				}
				$information = json_decode($event_info);
				/*
				THIS is the point in the file where the description and title portion of the event are put into the Naive Bayes classifyer, which determines whether it is an event with free food or not.  
				*/
				$free_food = $classifier->categorize($information->summary." ".$information->description);
				if($free_food) {
					printf("YES:   ".$information->summary."\n<br/>");	
				} else {
					printf("NO:   ".$information->summary."\n<br/>");
					continue;
				}

				$evnt = array();
				$evnt['id'] = $information->id;
				$evnt['link'] = (isset($information->link) ? $information->link : "https://today.brown.edu/events/".$information->id);
				$evnt['location'] = $information->location;
				$evnt['description'] = $information->description;
				$evnt['title'] = $information->summary;
				$evnt['time_start'] = strtotime($information->start);
				$evnt['time_end'] = ($information->start == $information->end ? NULL : strtotime($information->end));
				$evnt['date_int'] = date('Ymd', strtotime($information->start));

				if($mysqli->query("SELECT * FROM plugin__event WHERE date_int=".$evnt['date_int']." AND brown_event_id=".$evnt['id']."")) { //If there is overlap, do not add this event. 
					printf("Duplicate found<br/>");
					continue;
				}

				//Insert into the database
				if (!$mysqli->query(
					"INSERT INTO plugin__event 
						(
							visible, 
							brown_event_id, 
							title, 
							user_id, 
							time_start, 
							time_end, 
							group_title, 
							location, 
							link, 
							date_int
						) 
					VALUES 
						(
							0,
							".$evnt['id'].",
							'".$evnt['title']."',
							0,
							".$evnt['time_start'].",
							".$evnt['time_end'].",
							'Today@Brown',
							'".$evnt['location']."',
							'".$evnt['link']."',
							".$evnt['date_int']."
						)
				")) printf("ERROR INSERT<br/>");
				unset($evnt);
			}
			
			unset($classifier);

			break; //Only want the most recent email, maybe change this for training and data collection. 
		}
	}
}
?>