<?php

abstract class BaseController
{

    private $model;

    protected function model($modelName)
    {
        $this->model = new $modelName;
        return $this->model;
    }

    protected function view($view, $data = [])
    {
        require_once '../app/views/' . $view . '.php';
    }


}
