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
					printf('Adding New Event<br/>');

					//Copy the information over
					$new_event = new Event;
					$new_event->brown_event_id = $event->id;
					$new_event->title = $event->title;
					$new_event->time_start = $event->ts_start;
					if(!$event->ts_end) {
						$end = NULL;
					} else {
						$end = $event->ts_end;
					}
					$new_event->time_end = $end;
					$new_event->group_title = $event->group_title;
					$new_event->location = $event->location;
					$new_event->link = "https://events.brown.edu/".$event->href;
					$new_event->date_int = $day_info;
					//Try to add the event
					try {
						if($new_event->save() == false) {
							foreach ($new_event->getMessages() as $message) {
                    			$this->flash->error($message);
                			}
                			return $this->forward('admin');
						}
					} catch (exception $e) {
			            print_r($e);
			            die();
			            $this->flash->error('Special internal error');
			            return $this->forward('offer/new');
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
}