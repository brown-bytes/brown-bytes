<?php

use Phalcon\Mvc\Model;


class Transaction extends Model
{
    public function initialize() {
        $this->setSource('cms__user_address');
    }

    public function isParty() {
        Address::find("address = '".$this->recipient_address."'","address = '".$this->sender_address."'")
    }
}
