<?php

require_once('../vendor/autoload.php');
require_once('../vendor/simple_html_dom/simple_html_dom.php');
require_once('manualTextAnalysis.php');
require_once('../app/library/mailer.php');

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
$mbox = imap_open('{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX', 'jebc3s9fflbhhkez@gmail.com','O2je!iEf9q2pK&') or die('Cannot connect to Gmail: ' . imap_last_error());
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
				$link = substr($a->href, 0, 24).'api/v1/'.substr($a->href, 24, (strpos($a->href, '?')-24)); //Put in the api key to get only the good info
				print_r($link);
				$event_links[] = $link;
			}
			$html->clear();
			unset($html);
			unset($body);
			unset($overview);

			//We only want to create the classifier once. 
			//$classifier = new \Niiknow\Bayes(); //Create a classifier
			//$classifier->fromJson($json_weights); // load the classifier back from its JSON representation of weights.

			//Load in the new classifier
			$classifier = new manualClassifier();

			//This builds the informational email to scott@huson.com
			$email_content = "<b>Has free food: </b><br/>";
			//Build associative arrays for each link. 
			foreach($event_links as $event_link) {
				$event_info = file_get_contents($event_link);
				//Check if failed
				if(!$event_info) {
					printf("Failed getting event");
					continue;
				}
				$information = json_decode($event_info);
				$parse_string = preg_replace('/[^\da-z]/i', ' ', $information->summary." ".$information->description);
				$parse_string = strtolower($parse_string);
				/*
				THIS is the point in the file where the description and title portion of the event are put into the Naive Bayes classifyer, which determines whether it is an event with free food or not.  
				*/
				//$free_food = $classifier->categorize($information->summary." ".$information->description);
				$free_food = $classifier->classify($parse_string);
				if($free_food) {

					printf("YES:   ".$information->summary."\n<br/>");
					$email_content .= $information->summary."<br/>Key Words: ".join(' ', $free_food)."<br/>";
				} else {
					printf("NO:   ".$information->summary."\n<br/>");
					$classifier->categorize_no_food($parse_string);
					continue;
				}

				$evnt = array();
				$evnt['id'] = (int)$information->id;
				$evnt['link'] = (isset($information->link) ? addslashes($information->link) : "https://today.brown.edu/events/".$evnt['id']);
				$evnt['location'] = addslashes($information->location);
				$evnt['description'] = addslashes($information->description);
				$evnt['title'] = addslashes($information->summary);
				$evnt['time_start'] = strtotime($information->start);
				$evnt['time_end'] = ($information->start == $information->end ? NULL : strtotime($information->end));
				$evnt['date_int'] = date('Ymd', strtotime($information->start) - 14400);

				$result = $mysqli->query("SELECT * FROM plugin__event WHERE date_int=".$evnt['date_int']." AND brown_event_id=".$evnt['id']);
				if($result->num_rows) { //If there is overlap, do not add this event. 
					printf("Duplicate found<br/>\n");
					continue;
				}
				//Add to the free food file:
				$classifier->categorize_yes_food($parse_string);

				//Insert into the database
				$string = "INSERT INTO plugin__event 
						(
							visible, 
							brown_event_id, 
							title, 
							user_id, 
							time_start, 
							".($evnt['time_end'] ? "time_end," : "")." 
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
							".($evnt['time_end'] ? $evnt['time_end']."," : "")."
							'TodayAtBrown',
							'".$evnt['location']."',
							'".$evnt['link']."',
							".$evnt['date_int']."
						)";
				$result = $mysqli->query($string);

				if(!$result) printf($mysqli->error."\n");
				else printf("INSERTED<br/>\n");
				unset($evnt);
			}
			$email = new Mailer('scott@huson.com', 'Auto Scrape Complete', $email_content.'<br/>END.', False);
			unset($classifier);

			break; //Only want the most recent email, maybe change this for training and data collection. 
		}
	}
}
?>