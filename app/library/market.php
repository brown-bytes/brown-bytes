<?php

use Phalcon\Mvc\User\Component;

/**
 * Market
 *
 * Helps to build summary statistics and global functions for getting offers
 */
class Market extends Component {
    /**
     * Gets a summary of the market for the homepage 
     * Recent orders and such
     * @return Array
     **/
    public function getSnapshot() {
        return array('shit', 'shit');
    }

    /**
     * Checks whether a user has access to a transaction
     * @return Boolean
     **/
    public function hasAccess($txn_id) {
    	$market = new Market();


    	$tran = Transaction::findFirstByTxn_id($txn_id);
    	//If the query cannot find anything, or if access is denied, return false. 
    	return ($tran ? $tran->isParty() : false);
    	/* 
    	Equivalent code:
    	if ($tran) {
    		return $tran->isParty();
    	} else {
    		return false;
    	} */
    }
    /**
     * Gets information about a particular transaction
     * @return Array
     **/
    public function getTransaction($txn_id) {
    	$tran =  Transaction::findFirstByTxn_id($txn_id);

    	$sender = Address::findFirstByAddress($tran->sender_address);
    	$receiver = Address::findFirstByAddress($tran->recipient_address);
    	
    	$tran['sender'] = $sender->getUser();
    	$tran['receiver'] = $receiver->getUser();
    	
    	return $tran;
    }
    /**
     * Gets all the valid locations in the database
     * @return Array
     **/
    public function getVisibleLocations() {
    	//return array('shit');
    	$locations = Locations::findByVisible(1);
    	
    	return $locations;

    }
    /**
     * Gets all the users transactions from the database
     * @return Array
     **/
    public function getUserTransactions() {
        $user_id = $this->session->get('auth')['id'];
        /* The query I want to execute
            SELECT trans.*, add.* FROM cms__user_address add LEFT JOIN plugins__transactions trans ON add.address=trans.sender_address WHERE add.user_id={$user_id}
            //WHY THE FUCK IS IT SO HARD FOR PHALCON TO DO THAT?
        */



    }
    /**
     * Searches for transactions with very specific parameters
     * @return Array
     **/
    public function searchTransactions() {

    }
}
