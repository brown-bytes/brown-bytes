<?php

use Phalcon\Mvc\Model;

class Address extends Model
{
    

    public function initialize()
    {
        $this->setSource('cms__user_address');
    }

    public function getUser() {
        if (!$this->anonymous) {
            return $this->user_id;
        } else {
            return false;
        }
    }

}
