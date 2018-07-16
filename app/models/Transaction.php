<?php

use Phalcon\Mvc\Model;

//fucking shit
Model::setup(
		['notNullValidations' => false]
	);
class Transaction extends Model
{

	private $id; 

	private $sender_address;

	private $recipient_address;

	public $description;

	public $txn_id;

	public $amount;

	public $location_id;

	public $status;

	private $platform;

	public $timestamp;
    
    public function initialize() {
        $this->setSource('plugin__transaction');
    }
    //Determines whether the current user is a member of the transaction
    public function isParty() {
    	$current_user = $this->session->get('auth')['id'];

    	if ($this->session->admin)
    		return true;
        $addresses = Address::find("address = '".$this->recipient_address."'","address = '".$this->sender_address."'");
        foreach($addresses as $address) {
        	if ($address->$user_id = $current_user)
        		return true;
        }
        return false;
    }
}
