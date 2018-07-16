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
            SELECT trans.*, addr.* FROM plugin__transaction trans LEFT JOIN cms__user_address addr ON addr.address=trans.sender_address WHERE addr.user_id={$user_id}
            //WHY THE FUCK IS IT SO HARD FOR PHALCON TO DO THAT?
        */
        //This is the query builder, maybe I should use CMS that would be easier honestly
        $query = $this->modelsManager->createQuery('SELECT trans.*, addr.* FROM Transaction trans LEFT JOIN Address addr ON addr.address=trans.sender_address WHERE addr.user_id='.((int)$user_id));
        $result = $query->execute();
        
        /*//adds the transactions
        $builder->from(
            [
                'trans' => 'Transaction'
            ]
        );
        //joins the addresses
        $builder->join('Address', 'trans.sender_address = addr.address', 'addr', 'LEFT');
        //the where
        $builder->where('user_id = :user_id:', ['user_id' => $user_id]);
        $builder->orderBy('trans.timestamp DESC');
        $builder->limit(20);
        $transactions = $builder->getQuery();

        */
        foreach ($result as $tran) {
            var_dump($tran);
            die();
        }
        
        die();

    }
    /**
     * Searches for transactions with very specific parameters
     * @return Array
     **/
    public function searchTransactions() {

    }
}
