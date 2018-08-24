<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Select;

use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Between;



class OfferForm extends Form
{

    public function initialize(Offer $offer, $options = null)
    {
        // Description
        $description = new Text('title');
        $description->setLabel('Description:');
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
        
        $location = new Select('location', $loc_names);
        $location->setLabel('Location:');
        $this->add($location);

        $expires = new Numeric('expires');
        $expires->setLabel('Duration (hours):');
        $expires->setFilters(array('float'));
        $expires->addValidators(array(
            new Between(array(
                'minimum' => '1',
                'maximum' => '24',
                'message' => 'Please input a valid time period (1-24 hours)'
            ))
        ));
        $this->add($expires);

        // Anonymous
        $anonymous = new Check('anonymous');
        $anonymous->setLabel('Anonymous:');
        
        $this->add($anonymous);
    }
}