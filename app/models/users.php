<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;

class Users extends Model
{   
    private $id;

    private $name;

    private $admin;


    public function initialize()
    {
        $this->setSource('cms__users');
    }

    public function isAdmin() {
        return $this->admin;
    }

    public function validation()
    {   

        $this->validate(new EmailValidator(array(
            'field' => 'email'
        )));
        $this->validate(new UniquenessValidator(array(
            'field' => 'email',
            'message' => 'Sorry, The email was registered by another user'
        )));
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    public function getName() {
        return $this->name;
    }

    public function getId() {
        return $this->id;
    }
}
