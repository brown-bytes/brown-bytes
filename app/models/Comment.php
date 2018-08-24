<?php

use Phalcon\Mvc\Model;

//fucking shit
Model::setup(['notNullValidations' => false]);


class Comment extends Model
{

	private $id; 

    public $content;

    private $user_id;

    private $anonymous;

    public $offer_id;

    public $timestamp;
    
    public function initialize() {
        $this->setSource('plugin__comment');
    }

    public function getName() {
        if ($anonymous) {
            return 'Anonymous';
        } else {
            $user = Users::findFirstById($user_id);
            return $user->name;
        }
    }
    public function isOwner($id) {
        return ($id == $this->user_id);
    }
    public function isAnon() {
        return $this->anonymous;
    }
    public function getAuthor() {

        $user = Users::findFirstById($this->user_id);
        if($user) {
            return $user->name;
        }
        return 'No User';
    }
}
