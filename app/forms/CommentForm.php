<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

class CommentForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        //Comment
        $content = new TextArea('content',
            [
                'maxlength'   => 200,
                'placeholder' => 'Add a comment.',
            ]
        );
        $content->setLabel('  ');
        $content->setFilters(array('striptags', 'string'));
        $content->addValidators(array(
            new PresenceOf(array(
                'message' => 'No blank comments!'
            ))
        ));
        $this->add($content);

        // Anonymous
        $anonymous = new Check('anonymous');
        $anonymous->setLabel('Comment Anonymously');
        
        $this->add($anonymous);
    }
}