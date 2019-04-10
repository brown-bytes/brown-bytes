<?php

use Phalcon\Flash;
use Phalcon\Session;

class ScraperController extends ControllerBase
{
    public function initialize() {
        $this->tag->setTitle('Scraper');
        parent::initialize();
        //Check that you're an admin
        if(!$this->session->admin) {
        	//Send to unauthorized
        	return $this->forward('errors/show401');
        }
        //require_once(APP_PATH . 'vendor/simple_html_dom/simple_html_dom.php');
    }

    public function indexAction() {
        //Do the scraping here:
    	//Create DOM from URL or file
    	//The URL is long because theres a widget that determines the info that is included
    	$html = file_get_contents('https://events.brown.edu/live/calendar/view/all/categories/Free%20Food?user_tz=America%2FBelize&syntax=%3Cwidget%20type=%22events_calendar%22%3E%3Carg%20id=%22mini_cal_heat_map%22%3Etrue%3C/arg%3E%3Carg%20id=%22thumb_width%22%3E200%3C/arg%3E%3Carg%20id=%22thumb_height%22%3E200%3C/arg%3E%3Carg%20id=%22hide_repeats%22%3Etrue%3C/arg%3E%3Carg%20id=%22show_groups%22%3Etrue%3C/arg%3E%3Carg%20id=%22show_locations%22%3Etrue%3C/arg%3E%3Carg%20id=%22show_tags%22%3Etrue%3C/arg%3E%3Carg%20id=%22use_tag_classes%22%3Etrue%3C/arg%3E%3Carg%20id=%22search_all_events_only%22%3Etrue%3C/arg%3E%3Carg%20id=%22use_modular_templates%22%3Etrue%3C/arg%3E%3Carg%20id=%22display_all_day_events_last%22%3Etrue%3C/arg%3E%3C/widget%3E');

		//Check if failed
		if(!$html) {
			$this->flash->error('Could not get page');
			return $this->forward('admin/index');
		}

		//Get all the events
		$event_data = json_decode($html);

		if($event_data->event_count > 50) {
			//do something so that the rest are taken into account
		}

		//Counters to see how many events were added:
		$added = 0;
		$existing_int = 0;
		//This goes through all the days in which events occur
		foreach($event_data->events as $day_info => $day_events) {
			foreach($day_events as $event) {
				$existing = Event::findFirst("brown_event_id = ".$event->id);
				if(!$existing) {

					//Copy the information over
					$new_event = new Event;
					$new_event->brown_event_id = $event->id;
					$new_event->title = $event->title;
					$new_event->time_start = $event->ts_start;
					$new_event->time_end = (isset($event->ts) ? $event->ts_end : NULL);
					$new_event->group_title = $event->group_title;
					$new_event->location = (isset($event->location) ? $event->location : "TBD");
					$new_event->link = "https://events.brown.edu/".$event->href;
					$new_event->date_int = $day_info;
                    $new_event->user_id = 0;
					//Try to add the event
					try {
						if($new_event->save() == false) {
							foreach ($new_event->getMessages() as $message) {
                    			$this->flash->error($message);
                			}
                			return $this->forward('admin/index');
						}
					} catch (exception $e) {
			            print_r($e);
			            die();
			            $this->flash->error('Special internal error');
			            return $this->forward('admin/index');
			        }
					$added++;

				} else {
					$existing_int++;
				}	
			}
		} 
		$this->flash->success('Added: '.$added.', Existing: '.$existing_int);


    	//Flash the results of the scrape
        //Return back to the admin screen
        return $this->forward('admin/index');
    }

    public function eventAction($event_id = 0, $weeks = 0) {
        
    	if((int)$event_id == 0) {
    		$this->flash->success('Please add the event_id to the end of the url');
    		return $this->forward('admin/index');
    	} else {
    		//Puts the event_id in the link for the calendar
    		$link = "https://events.brown.edu/live/calendar/view/event/event_id/".$event_id."?user_tz=America%2FBelize&syntax=%3Cwidget%20type%3D%22events_calendar%22%3E%3Carg%20id%3D%22mini_cal_heat_map%22%3Efalse%3C%2Farg%3E%3Carg%20id%3D%22thumb_width%22%3E200%3C%2Farg%3E%3Carg%20id%3D%22thumb_height%22%3E200%3C%2Farg%3E%3Carg%20id%3D%22hide_repeats%22%3Etrue%3C%2Farg%3E%3Carg%20id%3D%22show_groups%22%3Etrue%3C%2Farg%3E%3Carg%20id%3D%22show_locations%22%3Etrue%3C%2Farg%3E%3Carg%20id%3D%22show_tags%22%3Etrue%3C%2Farg%3E%3Carg%20id%3D%22use_tag_classes%22%3Etrue%3C%2Farg%3E%3Carg%20id%3D%22search_all_events_only%22%3Etrue%3C%2Farg%3E%3Carg%20id%3D%22use_modular_templates%22%3Etrue%3C%2Farg%3E%3Carg%20id%3D%22display_all_day_events_last%22%3Etrue%3C%2Farg%3E%3C%2Fwidget%3E";
    		$html = file_get_contents($link);

    		//Edge Case Check
    		if($html == '"[]"') {
    			$this->flash->error('Invalid Event ID');
    			return $this->forward('admin/index');
    		}
    		$json = json_decode($html);
    		if(!$json) {
    			$this->flash->error('Could not decode JSON');
    			return $this->forward('admin/index');
    		}
            //Checks to see whether the event already exists
            $existing = Event::findFirst("brown_event_id = ".$event_id);
            if($existing) {
                $this->flash->error('Event is already in the calendar');
                return $this->forward('admin/index');
            }

    		$event = new Event;
    		//Set all the fields

    		$event->date_int = date('Ymd', strtotime($json->title));
    		if($event->date_int < date('Ymd')) {
    			$this->flash->error('Past event, cannot add to calendar: '.$event->date_int);
    			return $this->forward('admin/index');
    		}
    		$event->user_id = 0;
    		$event->brown_event_id = $event_id;
    		$event->link = 'https://events.brown.edu/#!view/event/event_id/'.$event_id;
    		$event->title = $json->event->title;
    		$event->time_start = $json->event->ts_start;
    		$event->time_end = $json->event->ts_end;
    		$event->location = $json->event->location;
    		$event->group_title = ($json->event->custom_event_sponsor == NULL ? $json->event->group_fullname : $json->event->custom_event_sponsor);
			
    		try {
                
				if($event->save() == false) {
					foreach ($event->getMessages() as $message) {
            			$this->flash->error($message);
        			}
        			return $this->forward('admin/index');
				}
			} catch (exception $e) {
	            $this->flash->error('Special internal error '.$e);
	            return $this->forward('admin/index');
	        }

	       	$this->flash->success('Added event with ID: '.$event_id);
	       	return $this->forward('admin/index');

    	}
    }
}