<?php

use Phalcon\Mvc\Model;


class Transaction extends Model
{
    public function initialize() {
        $this->setSource('plugin__transaction');
    }

    public function isParty() {
        Address::find("address = '".$this->recipient_address."'","address = '".$this->sender_address."'");
        return false;
    }
}
