<?php

/**
 * a singleton class to represent the http request
 */
class Request
{
    private $_method;
    static $_instance;

    private function __construct()
    {
        $this->_method = $_SERVER['REQUEST_METHOD'];
    }

    private function __clone() {}

    public static function getInstance()
    {
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function setPostVars()
    {
        //  is there really a need for a setter in this context?
    }

    // this is both setting and getting, because post vars are not a property of my
    // request object - is that ok?
    public function getPostVars()
    {
        if($this->_method === 'POST'){
            return $_POST;
        } else {
            return 0;
        }
    }
}
