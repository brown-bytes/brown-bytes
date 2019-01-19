<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Date as DateElement;

//require_once('Phalcon\Validation\Validator\Date');
//use Phalcon\Validation\Validator\Date as DateValidator;
use Phalcon\Validation\Validator\PresenceOf;
use \Phalcon\Validation\Validator\Callback;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\Url;



class EventForm extends Form
{

    public function initialize(Event $event, $options = null)
    {
        // title
        $title = new Text('title');
        $title->setLabel('Event Title:');
        $title->setFilters(array('striptags', 'string'));
        $title->addValidators(array(
            new PresenceOf(array(
                'message' => 'Title is required. Please find the official title of the event.'
            ))
        ));
        $this->add($title);
        // group
        $group = new Text('group_title');
        $group->setLabel('Host Group:');
        $group->setFilters(array('striptags', 'string'));
        $group->addValidators(array(
            new PresenceOf(array(
                'message' => 'Please enter the full group name.'
            ))
        ));
        $this->add($group);

        // location
        $location = new Text('location');
        $location->setLabel('Location:');
        $location->setFilters(array('striptags', 'string'));
        $location->addValidators(array(
            new PresenceOf(array(
                'message' => 'Location is required.'
            ))
        ));
        $this->add($location);

        // link
        $link = new Text('link');
        $link->setLabel('Link to Event Page:');
        $link->setFilters(array('striptags', 'string'));
        $link->addValidators(array(
            new PresenceOf(array(
                'message' => 'Link is required.'
            )),
            new Url(array(
                'message' => 'Invalid link, must be URL.'
            ))
        ));
        $this->add($link);

        // date and time
        $date_el = new DateElement('date');
        $date_el->setLabel('Event Date:');
        //add this back in
        /*$date_el->addValidators(array(
            new DateValidator(array(
                'message' => 'Not a valid date.'
            ))
        ));*/
        $this->add($date_el);

        $time_number = new Text('time_number');
        $time_number->setLabel('Start Time (format 8:00):');
        $time_number->setFilters(array('striptags', 'string'));
        $link->addValidators(array(
            new PresenceOf(array(
                'message' => 'Please enter a time.'
            )), 
            new Callback(
                [
                    'callback' => function($data) {
                        printf(strtotime($data));
                        return (strtotime($data) == false);
                    },
                    'message'  => "Sorry the parser couldn't recognize your format."
                ]
            )
        ));





    }
}