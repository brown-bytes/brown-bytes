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

    }
}
