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
        $this->setController($this->url_parts);
        $this->setMethod($this->url_parts);
        $this->setParams($this->url_parts);

        $this->executeControllerAction();
    }

    public function executeControllerAction()
    {
        //echo $this->createMessage();
        if (!class_exists($this->controller)){
            throw new Exception('No controller called ' . $this->controller);
        }
        if(!method_exists($this->controller, $this->method)) {
            throw new Exception($this->controller . ' has no action ' . $this->method);
        }

        $controller_callable = new $this->controller;

        call_user_func_array([$controller_callable, $this->method], $this->params);
    }

    public function parseUrl()
    {
        $url_elements = explode('/', $_SERVER['REQUEST_URI']);
        return $url_elements;
    }

    public function setController($url_parts)
    {
        if(isset($url_parts[1]) && !empty($url_parts[1])) {
            $this->controller = ucfirst($url_parts[1]) . 'Controller';
        }
        return $this;
    }

    public function setMethod($url_parts)
    {
        if(isset($url_parts[2])) {
            $this->method = $url_parts[2];
        }
        return $this;
    }

    public function setParams($url_parts)
    {
        if(array_key_exists(3, $url_parts)) {
            $this->params = array_slice($url_parts,3);
        }
        return $this;
    }

    public function createMessage()
    {
        $msg = $this->method . ' action of ' . $this->controller;
        if(count($this->params) > 0){
            $msg .= ' with params ' . implode(", ", $this->params);
        }
        return $msg;
    }
}
