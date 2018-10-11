<?php

class IndexController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Welcome');
        parent::initialize();
    }

    public function indexAction()
    {
    	$email = new Mailer('scott@huson.com', 'THis is a test', '<b>testing</b>');
    	echo $email->response;


    }
}
