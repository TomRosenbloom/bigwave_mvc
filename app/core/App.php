<?php

class App
{

    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    protected $url_elements;

    public function __construct()
    {
        $url_parts = $this->parseUrl();

        if(isset($url_parts[1])) {
            $controller_name = ucfirst($url_parts[1]) . 'Controller';
            if (class_exists($controller_name)) {
                $this->controller = $controller_name;
            }
            unset($url_parts[1]);
        }
        $this->controller = new $this->controller;

        if(isset($url_parts[2])) {
            $method_name = $url_parts[2];
            if(method_exists($this->controller, $method_name)){
                $this->method = $method_name;
            }
            unset($url_parts[2]);
        }

        $this->params = $url_parts ? array_values($url_parts) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl()
    {
        $url_elements = explode('/', $_SERVER['REQUEST_URI']);
        unset($url_elements[0]); // bodge
        return $url_elements;
    }

}
