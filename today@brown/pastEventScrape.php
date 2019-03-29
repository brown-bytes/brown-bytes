<?php
/*
This file scrapes descriptions and titles from thousands of past Brown Events, both with food and without.
*/
echo "starting\n";
require_once('../vendor/autoload.php');
require_once('../vendor/simple_html_dom/simple_html_dom.php');

$myfile = fopen("events-data.txt", "a") or die("Unable to open file!");
$counter = 0;
$start_date = '20190308';

$html = file_get_contents('https://events.brown.edu/live/calendar/view/all/date/'.$start_date.'?user_tz=America%2FBelize&syntax=%3Cwidget%20type=%22events_calendar%22%3E%3Carg%20id=%22mini_cal_heat_map%22%3Etrue%3C/arg%3E%3Carg%20id=%22thumb_width%22%3E200%3C/arg%3E%3Carg%20id=%22thumb_height%22%3E200%3C/arg%3E%3Carg%20id=%22hide_repeats%22%3Etrue%3C/arg%3E%3Carg%20id=%22show_groups%22%3Etrue%3C/arg%3E%3Carg%20id=%22show_locations%22%3Etrue%3C/arg%3E%3Carg%20id=%22show_tags%22%3Etrue%3C/arg%3E%3Carg%20id=%22use_tag_classes%22%3Etrue%3C/arg%3E%3Carg%20id=%22search_all_events_only%22%3Etrue%3C/arg%3E%3Carg%20id=%22use_modular_templates%22%3Etrue%3C/arg%3E%3Carg%20id=%22display_all_day_events_last%22%3Etrue%3C/arg%3E%3C/widget%3E%27');
//Check if failed
if(!$html) {
	die('Could not get main page');
}
//Get the objects
$json = json_decode($html);
unset($html);
//For all the days and all the events each day

foreach($json->events as $date => $day) {
	foreach($day as $key => $event) {
		//Get the information for an event
		$html = file_get_contents('https://events.brown.edu/live/calendar/view/event/event_id/'.$event->id.'?user_tz=America%2FBelize&syntax=%3Cwidget%20type%3D%22events_calendar%22%3E%3Carg%20id%3D%22mini_cal_heat_map%22%3Efalse%3C%2Farg%3E%3Carg%20id%3D%22thumb_width%22%3E200%3C%2Farg%3E%3Carg%20id%3D%22thumb_height%22%3E200%3C%2Farg%3E%3Carg%20id%3D%22hide_repeats%22%3Etrue%3C%2Farg%3E%3Carg%20id%3D%22show_groups%22%3Etrue%3C%2Farg%3E%3Carg%20id%3D%22show_locations%22%3Etrue%3C%2Farg%3E%3Carg%20id%3D%22show_tags%22%3Etrue%3C%2Farg%3E%3Carg%20id%3D%22use_tag_classes%22%3Etrue%3C%2Farg%3E%3Carg%20id%3D%22search_all_events_only%22%3Etrue%3C%2Farg%3E%3Carg%20id%3D%22use_modular_templates%22%3Etrue%3C%2Farg%3E%3Carg%20id%3D%22display_all_day_events_last%22%3Etrue%3C%2Farg%3E%3C%2Fwidget%3E');
		if(!$html) {
			printf("Couldn't get event ".$event->id.'<br/>');
			continue;
		}
		//echo $event->id."\n";
		//Get the description and clean it
		$event_json = json_decode($html);
		unset($html);

		try {
			$text = $event_json->event->description." ".$event_json->event->title;
			if(!$text) continue;
			$text = strip_tags($text);
			$text = preg_replace("/[^A-Za-z0-9 ]/", '', $text);
			fwrite($myfile, /*$event->id.": ".*/$text."\n");
			$counter++;
		} catch (Exception $e) {
			printf("Couldn't get one\n");
			continue;
		}
		
		unset($text);
		unset($event_json);
	}
	echo "Finished ".$date.", Counter = ".$counter."\n";
}

fclose($myfile);
?>