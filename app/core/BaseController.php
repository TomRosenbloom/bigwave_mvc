<?php

abstract class BaseController
{

    private $model;

    protected function model($modelName)
    {
        $this->model = new $modelName;
        return $this->model;
    }

    protected function getValidationRules()
    {
        return $this->model->validation_rules;
    }

    protected function isValid($post_data){
        $rules = $this->getValidationRules();
        return filter_input_array(INPUT_POST, $rules);
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

    public function getRequest()
    {
        return Request::getInstance();
    }


    // add form validation (etc) methods in here?
    // or in Request class?
    // or in a form controller?
    // or somewhere else?
    // public function getPostVars()
    // {
    //
    // }
}
