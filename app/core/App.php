<?php

class App
{
    protected $url_parts;
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $this->url_parts = $this->parseUrl();

        $this->controller = $this->setController();

        $this->setMethod();

        $this->params = $this->url_parts ? array_values($this->url_parts) : [];

        echo $this->method . ' action of ' . $this->controller->getName() . ' with params ' . implode(" ", $this->url_parts);

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl()
    {
        $url_elements = explode('/', $_SERVER['REQUEST_URI']);
        unset($url_elements[0]); // bodge
        return $url_elements;
    }

    public function setController()
    {
        if(isset($this->url_parts[1])) {
            $controller_name = ucfirst($this->url_parts[1]) . 'Controller';
            if (class_exists($controller_name)) {
                unset($this->url_parts[1]);
                return new $controller_name($controller_name);
            }

        }
        // $this->controller = new $this->controller;
        // return new $this->controller;
    }

    public function setMethod()
    {
        if(isset($this->url_parts[2])) {
            $method_name = $this->url_parts[2];
            if(method_exists($this->controller, $method_name)){
                $this->method = $method_name;
            }
            unset($this->url_parts[2]);
        }
    }

    public function getName()
    {
        return $this->name;
    }
}
