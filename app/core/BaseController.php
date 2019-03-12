<?php

//namespace App;

abstract class BaseController
{

    public function index()
    {
        echo "No index method defined for ". static::class;
    }

    /**
     * this is super primitive...
     * Should create a View class to be instantiated and used here
     * ...something that has methods to take view file name, view data, and then attempt to render a view
     *
     * @param  [type] $view [description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    protected function view($view, $data = [])
    {
        require_once '../app/views/' . $view . '.php';
    }

    /**
     * return the current request object
     * (to access methods for getting post vars etc)
     *
     * @return object
     */
    public function getRequest()
    {
        return Request::getInstance();
    }

}
