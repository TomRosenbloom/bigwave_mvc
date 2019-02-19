<?php

//namespace App;

abstract class BaseController
{
    protected $modelName;
    protected $model;
    
    function __construct() {
        $this->model = new $this->modelName;
    }

    protected function model($modelName)
    {
        $this->model = new $modelName;
        return $this->model;
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
