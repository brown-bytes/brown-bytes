<?php

use Phalcon\Mvc\Model;

class Address extends Model
{
    private $id;

    public $address;

    private $anonymous;

    private $user_id;

    private $timestamp;

    public function beforeSave(){
        
    }

    public function initialize()
    {
        $this->setSource('cms__user_address');
    }

    public function getUser() {
        if (!$this->anonymous) {
            return $this->user_id;
        } else {
            return false;
        }
    }

}
