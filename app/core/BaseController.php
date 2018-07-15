<?php

class BaseController
{
    protected function model($model)
    {
        echo $model, "<br>";
        $model = new $model;
        return $model;
    }
}
