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

    public function getUserId(){
        return $this->user_id;
    }
    public function getId() {
        return $this->id;
    }
    public function getName() {
        if ($anonymous) {
            return 'Anonymous';
        } else {
            $user = Users::findFirstById($user_id);
            return $user->name;
        }
    }

    public function getComments($id) {
        $comments = Comment::findByOffer_id($this->id);

        if($comments) {
            foreach ($comments as $comment) {
                //This filters for the anonymousness of the comments so much for get anonymous

                $return[] = array('content'=> $comment->content,'owned'=> ($comment->isOwner($id)), 'author' => ($comment->isAnon() ? 'Anonymous' : $comment->getAuthor()));

            }
            return $return;
        }
        return array();
    }

    public function hasAccess($user) {
        if ($user == $this->user_id || $this->expires > time()) {
            return true;
        }
        return false;
    }

    public function isOwner($user) {
        return ($user == $this->user_id);
    }
    public function getActivity() {
        $comments = Comment::find("offer_id = '".$this->id."'");
        return count($comments);

    }
}
