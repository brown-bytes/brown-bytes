<?php

use Phalcon\Http\Request;

/**
 * TransactionController
 *
 * Represents a transaction
 */
class TransactionController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Offer Details');
        parent::initialize();
    }

    public function indexAction()
    {
        $market = new Market();
        $this->view->transactions = $market->getUserTransactions();
    }
    public function infoAction($txn_id=null) {
        $market = new Market();

        //checks for access 
        if ($market->hasAccess($txn_id) && $txn_id != null) {
            echo 'great this is yours';
            //checks to see if the transaction exists (it should unless funny business)
            $data =  $market->getTransaction($txn_id);
            if (!$data) {
                $this->flash->error('A fatal error has occurred, please pray for the devs.');
            } else {
                $this->view->trans_data = $data;
            }
        } else {
            return $this->forward('transaction/index');
        }
    }


    /*
    * displays the creating transaction form
    *
    */
    public function newAction() {
        $this->view->form = new TransactionForm;
    }

    /*
    * Creates a transaction in the database
    *
    */
    public function createAction() {

        

        if (!$this->request->isPost()) {
            return $this->forward("transaction/new");
        }

        $form = new TransactionForm;
        $transaction = new Transaction();
        //This fucking thing was preventing nulls

        $data = $this->request->getPost();
        if (!$form->isValid($data, $transaction)) {
            $this->flash->error('form is not valid');
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('transaction/new');
        }

        //This is where we generate the transaction data
        $transaction->recipient_address = '';
        $transaction->txn_id = uniqid();
        $transaction->status = 0;
        $transaction->platform = 1;

        if ($data['type'] == 1) {
            $transaction->amount = ($transaction->amount * 7.85);
        }

        $address = new Address();
        if (isset($data['anonymous']) && $data['anonymous'] == 'on') {
            $address->address = md5(uniqid());
            $address->anonymous = 1;
            $address->user_id = $this->session->get('auth')['id'];
        } else {
            $address->address = md5(uniqid());
            $address->anonymous = 0;
            $address->user_id = $this->session->get('auth')['id'];
        }
        
        $transaction->sender_address = $address->address;


        if ($transaction->save() == false) {
            foreach ($transaction->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('transaction/new');
        }

        $form->clear();
        

        if ($address->save() == false) {
            foreach ($address->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('transaction/new');
        }

        

        $this->flash->success("Transaction was created successfully");
        return $this->forward("transaction/info");
    }

    private function canAccessAction() {
        
    }
}
