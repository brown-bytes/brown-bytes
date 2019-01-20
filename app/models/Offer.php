<?php

use Phalcon\Mvc\Model;


//fucking shit
Model::setup(['notNullValidations' => false]);

class Offer extends Model
{

	private $id; 

    public $title;

    public $location;

    private $user_id;

    public $anonymous;

    public $expires;

    public $timestamp;
    
    public function initialize() {
        $this->setSource('plugin__offer');
    }
    //This function sets up all the fields that are background, like created (unix timestamp), so that they dont jumble the create offer action. 
    public function beforeValidationOnCreate() {
        $this->created = time();
    }
    //gets the user_id
    public function getUserId(){
        return $this->user_id;
    }
    //Sets the user_id of an offer
    public function setUserId($user_id) {
        $this->user_id = $user_id;
        return true;
    }
    //Get the id of an offer
    public function getId() {
        return $this->id;
    }
    //Get the name of the creator of an offer, but not if its anonymous
    public function getName() {
        if ($anonymous) {
            return 'Anonymous';
        } else {
            $user = Users::findFirstById($user_id);
            return $user->name;
        }
    }
    //Get an array of comments on an offer
    public function getComments($id) {
        $comments = Comment::findByOffer_id($this->id);

        if($comments) {
            $return = array();
            foreach ($comments as $comment) {
                //This filters for the anonymousness of the comments so much for get anonymous

                $return[] = array('content'=> $comment->content,'owned'=> ($comment->isOwner($id)), 'author' => ($comment->isAnon() ? 'Anonymous' : $comment->getAuthor()));

            }
            return $return;
        }
        return array();
    }
    //Does the user have access to the offer
    public function hasAccess($user) {
        if ($user == $this->user_id || $this->expires > time()) {
            return true;
        }
        return false;
    }
    //Tests if a user is the owner of the offer
    public function isOwner($user) {
        return ($user == $this->user_id);
    }
    //Gets number of comments on an offer
    public function getActivity() {
        $comments = Comment::find("offer_id = '".$this->id."'");
        return count($comments);
    }
}
