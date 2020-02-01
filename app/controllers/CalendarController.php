<?php

use Phalcon\Flash;
use Phalcon\Session;


class CalendarController extends ControllerBase
{
    public function initialize() {

        $this->tag->setTitle('Calendar');
        parent::initialize();
        $this->view->admin = false;
        if ($this->session->get('auth')) {
        	$this->view->login = true;
        	$this->view->user = $this->session->get('auth')['data']['name'];
            if($this->session->curator) {
                $this->view->admin = true;
            }
        } else {
        	$this->view->login = false;
        }
    }

    public function indexAction() {
    	//See if there are offers because those should be suggested above
    	$market = new Market();
    	$offers = $market->getSnapshot(); //Max is 7 offers (if it ever gets that high at one time)
        if($this->session->curator) $number_of_events = 200;
        else $number_of_events = 30;
        $this->view->offers = $offers;

        $TIME_OFFSET = 18000;


        // $sql = "
        //     SELECT 
        //         id,
        //         title,
        //         user_id, 
        //         visible,
        //         time_start,
        //         time_end,
        //         location, 
        //         link,
        //         date_int
        //     FROM Event 
        //     WHERE date_int >= ".$this->getDateString().($this->session->curator ? "" : " AND visible = 1").
        //     " ORDER BY time_start ASC LIMIT ".$number_of_events;
        // $events = $this->modelsManager->executeQuery($sql);
        $date_query_string = "date_int >= ".$this->getDateString().($this->session->curator ? "" : " AND visible = 1");
        //Get all the calendar events:
        $events = Event::find( 
            [
            $date_query_string,
            'limit' => $number_of_events,
            'order' => 'time_start ASC'
            ]
        );
        $events_formatted = [];
        foreach ($events as $ev) {
            // Change all the dates
            $ev->id = $ev->id*2+69;
            $ev->day_string = date('l, F j', $ev->time_start - $TIME_OFFSET);
            $ev->time_start = date('g:i A', $ev->time_start - $TIME_OFFSET);
            $ev->time_end = date('g:i A', $ev->time_end - $TIME_OFFSET);
        }
        if ($this->request->isPost()) {
            // Make sure the post is from one of our apps
            // Want to make this inconspicuous so will just use queries
            $ip = $_SERVER['REMOTE_ADDR'];
            
            //$sql = "INSERT INTO plugin__app_request (";
            //$events = $this->modelsManager->executeQuery($sql);
            // Record information that the post happened
            echo json_encode($events_formatted);
            die(); //Only output json, nothing else
        } else {
            $this->view->events = $events_formatted;
        }
    }
    //Create a new event
    public function newAction() {
        $this->view->form = new EventForm(new Event);
    }

    public function createAction() {
        
        if (!$this->request->isPost()) {
            return $this->forward("calendar/new");
        }
        $TIME_OFFSET = 18000;

        $form = new EventForm(new Event);
        $event = new Event();
        try {
            $data = $this->request->getPost();
            if (!$form->isValid($data, $event)) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
                return $this->forward('calendar/new');
            }
        } catch (Exception $e) {
            printf("The server has encountered an error:<br/>".$e);
            die();
        }


        //Set all the fields here:
        $event->date_int = substr($event->date, 0, 4).substr($event->date, 5, 2).substr($event->date, 8, 2); //This is the date string for comparison
        $original_time_start = strtotime($event->date_int.'T'.$event->time_number) + $TIME_OFFSET;
        if($original_time_start < time()) {
            $this->flash->error('Please only use future dates.');
            return $this->forward('calendar/new');
        }
        $original_time_end = strtotime($event->date_int.'T'.$event->time_number_end) + $TIME_OFFSET;
        if($original_time_start > $original_time_end) {
            $this->flash->error('End time must be after start.');
            return $this->forward('calendar/new');
        }
        //get the brown event id:
        $pos = strrpos($event->link, 'event_id/');
        if($pos) {
            $event->brown_event_id = substr($event->link, $pos + 9);
        } else {
            $event->brown_event_id = 0;
        }
        $event->user_id = $this->session->get('auth')['id'];

        //Try to save it
        try {
            
            for($i = 0; $i <= $event->repeat; $i++) {
                
                $new_event = new Event();

                //Can we automate?
                $new_event->user_id = $event->user_id;
                $new_event->title = $event->title;
                $new_event->location = $event->location;
                $new_event->group_title = $event->group_title;
                $new_event->link = $event->link;
                $new_event->brown_event_id = 0;

                $new_event->time_start = strtotime('+'.$i.' Week', $original_time_start);
                $new_event->time_end = strtotime('+'.$i.' Week', $original_time_end);
                $new_event->date_int = date('Ymd', $new_event->time_start - $TIME_OFFSET); // This is the time difference from UTC or 4 hours
                
                if($this->session->curator && $new_event->date_int != $event->date_int) {
                    $this->flash->error('Looks like the dates are messed up. ACTION REQUIRED!');

                }

                if ($new_event->save() == false) {
                    foreach ($new_event->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                    return $this->forward('calendar/new');
                }
            }
            $form->clear();
        } catch (exception $e) {
            print_r($e);
            die();
            $this->flash->error('Special internal error. Contact an admin.');
            return $this->forward('calendar/new');
        }
        $this->flash->success("Event was created successfully");
        return $this->forward("calendar/index");
    }
    //This function hides events from the public view
    public function hideAction($id) {
        if($id){
            $event = Event::findFirstById((int)$id);
            if(!$event){
                $this->flash->error("Invalid ID");
            } else {
                if(!$event->hide()) {
                    $this->flash->error("Internal error: hiding failed");
                }
            }
        }
        return $this->forward("calendar/index");
    }
    //This function shows events in the public view
    public function showAction($id) {
        if($id){
            $event = Event::findFirstById((int)$id);
            if(!$event){
                $this->flash->error("Invalid ID");
            } else {
                $event->visible = 1;
                if(!$event->save()) {
                    $this->flash->error("Internal error: hiding failed");
                }
            }
        }
        return $this->forward("calendar/index");
    }

    //This function just returns the current date string.
    public function getDateString() {
    	//This accounts for the time zone change between GMT and EST
        return date('Ymd');
    }
}