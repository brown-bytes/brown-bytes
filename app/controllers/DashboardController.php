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
}
