<?php

use Phalcon\Mvc\Model;

//fucking shit
Model::setup(['notNullValidations' => false]);


class Event extends Model {
	
	public $id;

    public $brown_event_id;

    public $user_id;

    public $title;

    public $time_start;

    public $time_end;

   	public $group_title;

   	public $location;

   	public $link;

   	public $date_int;

   	public $timestamp;

    public function initialize()
    {
        $this->setSource('plugin__event');
    }
}