<?php

class MarketplaceController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Market');
        parent::initialize();

        if ($this->session->get('auth')) {
        	$this->view->login = true;
        	$this->view->user = $this->session->get('auth')['data']['name'];
        } else {
        	$this->view->login = false;
        }
    }

    public function indexAction()
    {	
    	$market = new Market();
    	$offers = $market->getCurrentOffers();

    	$this->view->offers = $offers;
    	

    }
}
