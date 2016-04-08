<?php

namespace RabeloWines\Form;

use Zend\Form\Form;

class SignupForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('signup');

        $this->add(array(
            'name' => 'email',
            'type' => 'Email',
            'attributes' => array(
                'placeholder' => 'Email',
            ),
            'options' => array(
                'label' => 'Email address',
            ),
        ));

        $this->add(array(
            'name' => 'listid',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Sign up',
                'id' => 'submitbutton',
            ),
        ));
    }
}