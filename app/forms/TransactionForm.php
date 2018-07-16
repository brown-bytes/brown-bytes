<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Select;

use Phalcon\Validation\Validator\PresenceOf;


class TransactionForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        // Description
        $description = new Text('description');
        $description->setLabel('Offer Description:');
        $description->setFilters(array('striptags', 'string'));
        $description->addValidators(array(
            new PresenceOf(array(
                'message' => 'Description is required. (Just write something simple)'
            ))
        ));
        $this->add($description);

        //Location
        $market = new Market();
        $locations = $market->getVisibleLocations();

        foreach($locations as $location) {
            $loc_names[$location->id] = $location->title;
        }
        
        $location = new Select('location_id', $loc_names);
        $location->setLabel('Offer Location:');
        $this->add($location);

        // Type
        $type = new Select('type',
            [
                1 => 'Swipes',
                2 => 'Points',
                3 => 'Both'
            ]);
        $type->setLabel('Type');
        $this->add($type);

        // Amount
        $amount = new Numeric('amount');
        $amount->setLabel('Amount');
        $amount->setFilters(array('float'));
        $amount->addValidators(array(
            new PresenceOf(array(
                'message' => 'Must be non-zero amount.'
            ))
        ));
        $this->add($amount);

        // Anonymous
        $anonymous = new Check('anonymous');
        $anonymous->setLabel('Anonymous');
        
        $this->add($anonymous);
    }
}