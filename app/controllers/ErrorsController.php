<?php

class ErrorsController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Shit!');
        parent::initialize();
    }

    public function show404Action()
    {
    }

    public function show401Action()
    {
    }

    public function show500Action()
    {
        $email = new Mailer('scott@huson.com', 'Brown Bytes Error Encountered', 'An error has been encountered:<br/>'.$_SERVER['REQUEST_URI']);
    }
}
