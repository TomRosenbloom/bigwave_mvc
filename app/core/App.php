<?php

//namespace App;

class App
{
    protected $url_tokens;
    protected $controller;
    protected $controllerName = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        session_start(); // session always on

        $router = new Router();
      
        $this->url_tokens = $router->parse($_SERVER['REQUEST_URI']);
        
        //var_dump($this->url_tokens); echo "<br>";
        
        $this->controller = $this->controllerFactory($this->url_tokens);
        
        $this->method = $this->setMethod($this->url_tokens);
        
//        $this->setParams($this->url_tokens);

        //$this->executeControllerAction();
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function controllerFactory(array $url_tokens)
    {
        if (!empty($url_tokens['controller'])) {
            if (class_exists(ucwords($url_tokens['controller']) . "Controller")) {
                $this->controllerName = ucwords($url_tokens['controller']) . "Controller";
            } else {
                throw new \Exception('No controller of name ' . $url_tokens['controller'] . "Controller");
            }
        }
        return new $this->controllerName;
    }
    
    public function setMethod(array $url_tokens)
    {
        if (!empty($url_tokens['action'])) {
            $method = ucwords($url_tokens['action']);
        } else {
            $method = 'index';
        }
        return $method;
    }    

    
    public function executeControllerAction()
    {
        var_dump($this->controller);
        var_dump($this->method);
//        if(is_callable($this->controller)) {
//            if(!empty($this->method)){
//                call_user_func_array([$this->controller, $this->method], $this->params);
//            } else {
//                var_dump($this->method);
//            }
//        }
        
        if (!is_callable($this->controller)){
            throw new \Exception('Not a callable controller');
        }
        if(!method_exists($this->controller, $this->method)) {
            throw new \Exception($this->controller . ' has no action ' . $this->method);
        }
        
        call_user_func_array([$this->controller, $this->method], $this->params);
    }


    public function setControllerName(array $url_tokens)
    {
        if (isset($url_tokens['controller'])) {
            if (class_exists(ucwords($url_tokens['controller']) . "Controller")) {
                $this->controllerName = ucwords($url_tokens['controller']) . "Controller";
            }
        }
    }    


    /**
     * extract params (everything after controller and action) from url
     *
     * [in many if not most basic MVC examples you'll see unset($url[0]) unset($url[1])
     * so that the remaining part is params - I had it that way originally but changed it
     * and I can't remember why exactly]
     *
     * @param [type] $url_parts [description]
     */
    public function setParams($url_parts)
    {
        if(array_key_exists(3, $url_parts)) {
            $this->params = array_slice($url_parts,3);
        }
        return $this;
    }

    /**
     * not an essential MVC component, just for testing
     *
     * @return [type] [description]
     */
    public function createMessage()
    {
        $msg = $this->method . ' action of ' . $this->controller;
        if(count($this->params) > 0){
            $msg .= ' with params ' . implode(", ", $this->params);
        }
        return $msg;
    }
}
