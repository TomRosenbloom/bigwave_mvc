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
        var_dump($this->url_tokens);
        
        $this->controller = $this->controllerFactory($this->url_tokens);
        var_dump($this->controller);
        
        $this->method = $this->setMethod($this->url_tokens);
        var_dump($this->method);
        
//        $this->setParams($this->url_tokens);

        //$this->executeControllerAction();
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function controllerFactory(array $url_tokens)
    {
        if (!empty($url_tokens['controller'])) {
            if (class_exists(ucwords($url_tokens['controller']) . "Controller")) {
                $this->controllerName = ucwords($url_tokens['controller']) . "Controller";
                //$controller = new $this->controllerName;
            } else {
                throw new \Exception('No controller called ' . $this->controller);
            }
        }
        return  new $this->controllerName;
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
        if (!class_exists($this->controller)){
            throw new \Exception('No controller called ' . $this->controller);
        }
        if(!method_exists($this->controller, $this->method)) {
            throw new \Exception($this->controller . ' has no action ' . $this->method);
        }

        $controller_callable = new $this->controller;

        call_user_func_array([$controller_callable, $this->method], $this->params);
    }

    
    // Many times it seems like we have a choice between returning the 
    // name of an object and then using that to instatiate, or returning 
    // an object directly
    // It feels like the latter is more 'correct'
    // 
    // Anyway in the current sitch, how do we get the action, and any params?
    // It doesn't make sense to return an action/method 'object', as it does for 
    // controller, because actions aren't classes
    
    // do not want to parse the url twice
    
//class ControllerFactory {
    public function createControllerFromRouter(Router $router) {
        $result = $router->parse($_SERVER['REQUEST_URI']);
        echo "<pre>", var_dump($result), "</pre>";
        if (isset($result['controller'])) {
            if (class_exists(ucwords($result['controller']) . "Controller")) {
                $controller = ucwords($result['controller']) . "Controller";
                return new $controller();
            }
        }
    }
//}    
  
    



//    public function parseUrl()
//    {
//        $url_elements = explode('/', $_SERVER['REQUEST_URI']);
//        return $url_elements;
//    }

    public function setControllerName(array $url_tokens)
    {
        if (isset($url_tokens['controller'])) {
            if (class_exists(ucwords($url_tokens['controller']) . "Controller")) {
                $this->controllerName = ucwords($url_tokens['controller']) . "Controller";
            }
        }
    }    


    
    
//    public function setController($url_parts)
//    {
//        if(isset($url_parts[1]) && !empty($url_parts[1])) {
//            $this->controller = ucfirst($url_parts[1]) . 'Controller';
//        }
//        return $this;
//    }

//    public function setMethod($url_parts)
//    {
//        if(isset($url_parts[2]) && !empty($url_parts[2])) {
//            $this->method = $url_parts[2];
//        }
//        return $this;
//    }

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
