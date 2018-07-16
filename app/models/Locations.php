<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;

class Locations extends Model
{   
    public $id;

    private $visible;

    public $title;

    public $swipes;

    public $points;


    public function initialize()
    {
        $this->setSource('plugin__location');
    }

    public function getVisible() {
        return $visible;
    }
}
