<?php

abstract class BaseController
{

    private $model;

    protected function model($model)
    {
        $this->model = new $model;
        return $this->model;
    }

    protected function view($view, $data = [])
    {
        require_once '../app/views/' . $view . '.php';
    }


}
