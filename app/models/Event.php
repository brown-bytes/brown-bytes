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

    public function initialize() {
        $this->setSource('plugin__event');
    }
    public function addToGCal() {
        $start = date('YmdHis', $this->time_start);
        $end = date('YmdHis', $this->time_end);
        $string = "http://www.google.com/calendar/event?action=TEMPLATE&text=BB:".preg_replace("/&#?[a-z0-9]+;/i","",$this->title)."&dates=".substr($start, 0, 8)."T".substr($start, 8)."Z"."/".substr($end, 0, 8)."T".substr($end, 8)."Z"."&details=This event was brought to you by Brown Bytes. Make sure to check that the food at this event aligns with your dietary restrictions or preferences.&location=".$this->location."&trp=false&sprop=&sprop=brownbytes:";
        return $string;
        //http://www.google.com/calendar/event?action=TEMPLATE&text=BB:Nicholas%20Barnes%20%E2%80%93%20The%20Origins%20of%20Gang%20Governance%20in%20Rio%20de%20Janeiro&dates=20190306T180000Z/19700101T010000Z&details=This%20event%20was%20brought%20to%20you%20by%20Brown%20Bytes.%20Make%20sure%20to%20check%20that%20this%20food%20at%20this%20event%20aligns%20with%20your%20dietary%20restrictions%20or%20preferences.&location=Watson%20Institute%20for%20International%20and%20Public%20Affairs&trp=false&sprop=&sprop=brownbytes:
    }
}