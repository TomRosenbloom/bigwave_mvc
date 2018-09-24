<?php

abstract class BaseController
{

    protected function model($model)
    {
        $model = new $model;
        return $model;
    }

    protected function view($view, $data = [])
    {
        require_once '../app/views/' . $view . '.php';
    }


}
