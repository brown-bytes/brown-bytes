<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;

class Users extends Model
{   
    protected $id;
    public $name;
    public $email;
    public $admin;
    public $status;
    public $created_on;
    public $last_login;
    public $verify;
    public $api_private;
    private $password;


    public function initialize()
    {
        $this->setSource('cms__users');
        $timestamp = new Phalcon\Db\RawValue('now()');
        $this->created_on = $timestamp;
        $this->last_login = $timestamp;
        $this->timestamp = $timestamp;
        $this->api_private = md5(uniqid(rand(), true));
        $this->status = 0;
        $this->admin = 0;
    }

    public function isAdmin() {
        return $this->admin;
    }

    

    public function getName() {
        return $this->name;
    }

    public function getId() {
        return $this->id;
    }
    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        if($this->password)
            return true;
        else
            return false;
    }
    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }
}
