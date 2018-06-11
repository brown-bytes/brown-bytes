<?php 



use Phalcon\Mvc\Model;

class Users extends Model
{
    public function initialize() {
    	$this->setSource('cms__user');
    }

}



?>