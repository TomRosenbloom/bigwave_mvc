<?php

class App
{

    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];

    protected $url_elements;

    public function __construct()
    {
        echo "this is the constructor of the app core<br>";
        print_r($this->parseUrl());

        $url_parts = $this->parseUrl();

        if(isset($url_parts[1])) {
            $controller_name = ucfirst($url_parts[1]) . 'Controller';
            if (class_exists($controller_name)) {
                echo "found $controller_name<br>";
                $this->controller = $controller_name;
            }
        }
        $this->controller = new $this->controller;

        if(isset($url_parts[2])) {
            $method_name = $url_parts[2];
            if(method_exists($this->controller, $method_name)){
                $this->method = $method_name;
            }
        }

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl()
    {
        return $this->url_elements = explode('/', $_SERVER['REQUEST_URI']);

    }

}
