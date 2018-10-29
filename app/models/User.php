<?php

class User extends BaseModel
{

    public function __construct()
    {
        parent::__construct('users');
        $this->setValidationRules();
    }

    /// should be defined in some way/to some extent higher up the inheritance
    /// this will do for now
    public function setValidationRules()
    {
        // $this->validation_rules = array(
        //     'name' => FILTER_SANITIZE_STRING,
        //     'email' => FILTER_SANITIZE_EMAIL,
        //     'password' => FILTER_SANITIZE_STRING
        // );
        $this->validation_rules = array(
            'name' => array('type' => 'string'),
            'email' => array('type' => 'email'),
            'password' => array('type' => 'string')
        );
    }

}
