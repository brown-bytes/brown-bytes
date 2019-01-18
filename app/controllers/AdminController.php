<?php

use Phalcon\Flash;
use Phalcon\Session;

class AdminController extends ControllerBase
{
    public function initialize() {
        $this->tag->setTitle('Admin');
        parent::initialize();
    }

    public function indexAction() {
        //Admin Shit Here
        
    }
}