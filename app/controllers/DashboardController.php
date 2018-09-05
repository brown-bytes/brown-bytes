<?php

use Phalcon\Flash;
use Phalcon\Session;

class DashboardController extends ControllerBase
{
    public function initialize() {
        $this->tag->setTitle('Manage your Profile');
        parent::initialize();
    }

    public function indexAction() {
        $market = new Market();
        $offers = $market->getOffers(array('user_id = '.$this->session->get('auth')['id'].' AND expires > '.time().' AND created < '.time()));

        $table_offers = array();
        foreach($offers as $offer) {
            $offer->expiration = $this->getRemaining($offer->expires, time());
            $offer->location = Locations::findFirstById($offer->location)->title;
            $offer->offer_id = $offer->getId();
            $offer->activity = $offer->getActivity();

            $table_offers[] = $offer;
        }
        
        
        $this->view->offers = $table_offers;
    }

    /**
     * Edit the active user profile
     *
     */
    public function profileAction()
    {
        //Get session info
        $auth = $this->session->get('auth');

        //Query the active user
        $user = Users::findFirstById($auth['id']);
        if ($user == false) {
            return $this->forward('index/index');
        }
        //I dont think BB should support name changes, only through customer support. 
    }
    public function privacyAction(){

    }
    public function offersAction() {
        $market = new Market();
        $offers = $market->getOffers(array('user_id = '.$this->session->get('auth')['id']));
        $table_offers = array();
        foreach($offers as $offer) {
            $offer->status = (time() >= $offer->expires ? 0 : 1);
            $offer->date = date('d-m-Y', strtotime($offer->timestamp));
            $offer->location = Locations::findFirstById($offer->location)->title;
            $offer->offer_id = $offer->getId();

            $table_offers[] = $offer;
        }


        $this->view->offers = $table_offers;
    }
    /**
     * Gets remaining time until a date
     *
     */
    public function getRemaining($time, $curr_time) {
        $difference = $time - $curr_time;
        //seconds
        if($difference <= 60) {
            return "Less than a minute";
        } 
        //minutes
        $difference = $difference/60;
        if (($difference) <= 60) { 
            return (int)$difference." minutes";
        }
        //hours
        $difference = $difference/60; 
        if (($difference) <= 24) { 
            return (int)$difference." hours";
        }

        $difference = $difference/24; 
        
        return (int)$difference." days";
    }
}
