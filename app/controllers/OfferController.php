<?php

use Phalcon\Http\Request;

/**
 * TransactionController
 *
 * Represents a transaction
 */
class OfferController extends ControllerBase
{
	public function initialize()
    {
        $this->tag->setTitle('Offer Details');
        parent::initialize();
    }
    //View the current offer
    public function indexAction($id=null) {
        $user = $this->session->get('auth')['id'];
        $this->view->id = $id;

        //var_dump(date('Y-m-d g:i A', time()));
        //die();
        if (!$id)
    		return $this->forward('marketplace');
        $offer = Offer::findFirstById($id);
        if (!$offer || !$offer->hasAccess($this->session->get('auth')['id'])) {
            $this->flash->error('The offer you have requested was not found or is no longer available to you.');
            return $this->forward('marketplace');
        } else {
            //loading the data for the layout generation
            $this->view->title = $offer->title;

            //is it active?
            $this->view->is_active = !($offer->expires <= time());
            


            //QUICK FIX ACTION REQUIRED
            $this->view->expires = date('Y-m-d g:i A', $offer->expires-21600);
            
            $this->view->comments = $offer->getComments($user);
            //$this->view->till_time = date('', $offer->expires)
            $this->view->is_owner = $offer->isOwner($user);
            $this->view->owner = ($offer->isOwner($user) ? "You" : "Not You");

            //For the commenting form
            $this->view->form = new CommentForm(new Comment);
        }
    }
    //Create a new offer
    public function newAction() {
    	$this->view->form = new OfferForm(new Offer);
    }
    //Action to create an offer with the given properties
    public function createAction() {
        if (!$this->request->isPost()) {
            return $this->forward("offer/new");
        }

        $form = new OfferForm(new Offer);
        $offer = new Offer();
        
        $data = $this->request->getPost();
        if (!$form->isValid($data, $offer)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('offer/new');
        }
        $offer->user_id = $this->session->get('auth')['id'];
        $offer->duration = $offer->expires;
        $offer->expires = (time() + $offer->expires*3600);
        $offer->anonymous = ($data['anonymous'] == 'on' ? 1 : 0);
        
        try {
            if ($offer->save() == false) {
                foreach ($offer->getMessages() as $message) {
                    $this->flash->error($message);
                }
                return $this->forward('offer/new');
            }
            $form->clear();
        } catch (exception $e) {
            print_r($e);
            die();
            $this->flash->error('Special internal error');
            return $this->forward('offer/new');
        }
        $this->flash->success("Offer was created successfully");
        return $this->forward("offer/index");
    }

    public function editAction($id=null) {
        if (!$id)
            return $this->forward('marketplace');
        $offer = Offer::findFirstById((int)$id);
        //var_dump(!$offer .' '.!$offer->isOwner($this->session->get('auth')['id']));
        //die();
        if (!$offer || !$offer->isOwner($this->session->get('auth')['id'])) {
            $this->flash->error('The offer you have requested was not found or is no longer available.');
            return $this->forward('marketplace');
        } else {
            //loading the data for the layout generation
            $offer->expires = $offer->duration;
            $this->view->form = new OfferForm($offer);

            //add the ID so it can be linked to 
            $this->view->offer_id = (int)$id;
        }
    }
    public function editedAction($id) {
        if (!$this->request->isPost()) {
            return $this->forward("offer/edit/".(int)$id);
        }
        $offer = Offer::findFirstById((int)$id);
        $expires = $offer->expires;
        $form = new OfferForm($offer);
        
        $data = $this->request->getPost();
        if (!$form->isValid($data, $offer)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('offer/edit/'.(int)$id);
        }

        $offer->duration = $offer->expires;
        $offer->expires = $expires;
        $offer->anonymous = ($data['anonymous'] == 'on' ? 1 : 0);
        
        try {
            if ($offer->save() == false) {
                foreach ($offer->getMessages() as $message) {
                    $this->flash->error($message);
                }
                return $this->forward('offer/new');
            }
            $form->clear();
        } catch (exception $e) {
            print_r($e);
            die();
            $this->flash->error('Special internal error');
            return $this->forward('offer/new');
        }
        $this->flash->success("Offer was edited successfully");
        return $this->forward("offer/index");
    }
    public function commentAction($id) {
        if (!$this->request->isPost()) {
            return $this->forward("offer/index/".(int)$id);
        }

        //If the user is not logged in...
        if (!$this->session->get('auth')) {
            $this->flash->error('You must be logged in to comment.');
            return $this->forward('session/index');
        }

        $form = new CommentForm(new Comment);
        $comment = new Comment();
        
        $data = $this->request->getPost();
        if (!$form->isValid($data, $comment)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('offer/index/'.(int)$id);
        }

        $comment->user_id = $this->session->get('auth')['id'];
        $comment->anonymous = (empty($data['anonymous']) && $data['anonymous'] == 'on' ? 1 : 0);
        $comment->offer_id = (int)$id;


        try {
            if ($comment->save() == false) {
                foreach ($comment->getMessages() as $message) {
                    $this->flash->error($message);
                }
                return $this->forward('offer/index/'.(int)$id);
            }
            $form->clear();
        } catch (exception $e) {
            print_r($e);
            die();
            $this->flash->error('Special internal error');
            return $this->forward('offer/index/'.(int)$id);
        }
        $this->flash->success("Commented Successfully!");
        return $this->forward("offer/index/".(int)$id);
    }

    //Action to deactivate an offer indefinitely
    public function deactivateAction($id) {
        //verify you are the owner of the offer and the offer exists
        $offer = Offer::findFirstById((int)$id);
        if(!$offer) {
            $this->flash->error("Offer not found.");
            return $this->forward("offer/index/".(int)$id);
        } else if($offer->getUserId() != $this->session->get('auth')['id']) {
            $this->flash->error("You do not have permission to complete this action.");
            return $this->forward("offer/index/".(int)$id);
        } else {
            $offer->expires = time();
            $offer->save();
            $this->flash->success("Offer Deactivated.");
            return $this->forward("offer/index/".(int)$id);
        }
    }
    //Action to activate an old offer for a set period
    public function activateAction($id, $hours = 1) {
        //verify you are the owner of the offer and the offer exists
        $offer = Offer::findFirstById((int)$id);
        if(!$offer) {
            $this->flash->error("Offer not found.");
            return $this->forward("offer/index/".(int)$id);
        } else if($offer->getUserId() != $this->session->get('auth')['id']) {
            $this->flash->error("You do not have permission to complete this action.");
            return $this->forward("offer/index/".(int)$id);
        } else {
            $offer->expires = time() + (3600*(int)$hours); 
            $offer->save();
            $this->flash->success("Offer Activated.");
            return $this->forward("offer/index/".(int)$id);
        }
    }

}