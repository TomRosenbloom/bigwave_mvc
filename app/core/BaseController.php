<?php

class BaseController
{
    protected $name;
    protected $model;
    protected $param;

    public function __construct($name)
    {
        // echo "foo method of bar controller<br>";
        // echo $param;
        $this->setName($name);
    }

    protected function model($model)
    {
        $model = new $model;
        return $model;
    }

    protected function view($view, $data)
    {
        require_once '../app/views/' . $view . '.php';
    }

    protected function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
