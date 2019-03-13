<?php

class Router {

    private $routes = array();
    
    /**
     * create routes in the constructor (fair enough, right?)
     * each route is defined with:
     * 1. a regex pattern
     * 2. an array of tokens to be taken from the regex
     * Round brackets in regex create capturing subpatterns
     * we will later use preg_match to match sub-patterns to tokens
     * 
     */
    public function __construct() 
    {
        $this->addRoute("/(admin)/(test)", array("controller", "action"));
        $this->addRoute("/(.*?)/(.*?)(?:/(.*)){0,}", array("controller", "action", "params"));       
        $this->addRoute("/(.*)/(.*)", array("controller", "action"));
        $this->addRoute("/(.*)", array("controller"));
  
        
    }

    /**
     * each route consists of a string pattern, and an array of 'tokens'
     * 
     * @param type $pattern
     * @param type $tokens
     */
    public function addRoute($pattern, $tokens = array()) 
    {
        $this->routes[] = array(
            "pattern" => $pattern,
            "tokens" => $tokens
        );
    }

    public function parse($url) 
    {
        $tokens = array();

        foreach ($this->routes as $route) {
            // work through the routes until we find one that matches the url
            // take tokens representing controller, action, params, from the route
            $pattern = "@^" . $route['pattern'] . "$@";
            preg_match($pattern, $url, $matches);
            if ($matches) {
                foreach ($matches as $key=>$match) {
                    // Not interested in the complete match, just the tokens
                    if ($key == 0) {
                        continue;
                    }
                    // Save the token
                    $tokens[$route['tokens'][$key-1]] = $match;
                }
                return $tokens;
            }
        }
        return $tokens;
    }
}
