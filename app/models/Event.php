<?php

use Phalcon\Mvc\Model;

//fucking shit
Model::setup(['notNullValidations' => false]);


class Event extends Model {
	
	  public $id;

    public $brown_event_id;

    public $visible;

    public $user_id;

    public $title;

    public $time_start;

    public $time_end;

   	public $group_title;

   	public $location;

   	public $link;

   	public $date_int;

   	public $timestamp;

    public function onConstruct() {
      $this->visible = 1;
    }

    public function initialize() {
        $this->setSource('plugin__event');
    }
    public function addToGCal() {
        //date_default_timezone_set("America/New_York");
        $start = date('YmdHis', $this->time_start);
        if ($this->time_end) {
          $end = date('YmdHis', $this->time_end);
        } else {
          $end = date('YmdHis', $this->time_start+3600);
        }
        $string = "http://www.google.com/calendar/event?action=TEMPLATE&text=BB :".preg_replace("/&#?[a-z0-9]+;/i","",$this->title)."&dates=".substr($start, 0, 8)."T".substr($start, 8)."Z"."/".substr($end, 0, 8)."T".substr($end, 8)."Z"."&details=This event was brought to you by Brown Bytes. Make sure to check that the food at this event aligns with your dietary restrictions or preferences.&location=".$this->location."&trp=false&sprop=&sprop=brownbytes:";
        return $string;
    }
    //This function hides an event
    public function hide() {
      $this->visible = 0;
      return $this->save();
    }
}