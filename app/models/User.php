<?php

class User extends BaseModel
{

    public function __construct()
    {
        parent::__construct('users');
        $this->setValidationRules();
    }


    /**
     * set validation rules for properties of this model
     * NB this should be done via some kind of contract/interface method
     * At the moment it's a bit arbitrary i.e. the validation methods in baseModel
     * depend on finding a key called 'type', but that isn't enforced anywhere
     * Also, by same token, any field names MUST correspond to the names used here,
     * but if they didn't, it would be a v difficult error to trace
     * A further issue: what happens when you want things in a form that don't
     * have a corresponding model property - for example a password confirmation field?
     */
    public function setValidationRules()
    {
        $this->validation_rules = array(
            'name' => array('type' => 'string'),
            'email' => array('type' => 'email'),
            'password' => array('type' => 'password')
        );
    }

}
