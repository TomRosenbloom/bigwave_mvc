<?php

class BaseController
{
    protected function model($model)
    {
        echo $model, "<br>";
        $model = new $model;
        return $model;
    }

    protected function view($view, $data)
    {
        echo "$view<br>";
        require_once '../app/views/' . $view . '.blade.php';
    }
}
