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
        $offers = Offer::find(
            array(
                ' expires > '.time(), 
                'order' => 'timestamp DESC',
                'limit' => 7
            )
        );
        //empty case
        $return = array();
        foreach($offers as $offer) {
            //This just gets basic info for the summary snapshot

            $location = Locations::findFirstById($offer->location);
            $offer->location_name = $location->title;

            $offer->expiration = round(($offer->expires - time()) / 3600, 2);

            $return[] = $offer;
        }
        return $return; 

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
        $query = $this->modelsManager->createQuery('SELECT trans.*, addr.*, IF(dest.anonymous=0, user.name, ) FROM Transaction trans LEFT JOIN Address addr ON addr.address=trans.sender_address LEFT JOIN Address dest ON dest.address=trans.recipient_address IF(dest.anonymous=1, LEFT JOIN cms__user ON cms__user.id=dest.user_id, ) WHERE addr.user_id='.((int)$user_id));
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
        $return = array();
        foreach ($result as $tran) {
            $return[] = $tran;
            var_dump($return);
            die();
        }
        
        die();
        return $tran;
    }

    /**
     * Searches for offers with very specific parameters
     * @return Array
     **/
    public function getCurrentOffers() {
        $offers = Offer::find(array(' expires > '.time(), 'order' => 'timestamp DESC'));

        //empty case
        $return = array();
        foreach($offers as $offer) {
            //This checks whether the user that created the offer marked it as anonymous
            if($offer->anonymous) {
                $offer->name = 'Anonymous';
            } else {
                //If the offer is not anonymous, then get the users name
                $user = Users::findFirstById($offer->user_id);
                $offer->name = $user->name;
            }

            //This checks whether the offer belongs to the current user. 
            //Maybe later I can add something where associated users can see that they are associated to the owner but cannot see their identity. 
            if($offer->isOwner($this->session->get('auth')['id'])) {
                $offer->is_owner = true;
            } else {
                $offer->is_owner = false;
            }
            //Sets the ID so that the offer can be linked to
            $offer->offer_id = $offer->getId();
            $location = Locations::findFirstById($offer->location);
            $offer->location_name = ($location ? $location->title : 'error');

            $return[] = $offer;
        }
        return $return; 
    }

    public function getOffers($params=array()) {
        $offers = Offer::find(array_merge($params, array('order' => 'timestamp DESC')));
        return $offers;
    }
}
