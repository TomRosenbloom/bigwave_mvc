<?php

//namespace App;

class UrlHelper
{
    function redirect($page){
        header('location: ' . URL_ROOT . '/' . $page);
    }

    /**
     * returns the path part of current url, excluding query string
     * 
     * @return string
     */
    function current(){
        $url_parts = explode('?',$_SERVER['REQUEST_URI']);
        return $url_parts[0];
    }
    
    /**
     * return query string from current url
     * 
     * @return string
     */
    function query_string(){
        return(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY));
    }
    
    /**
     * returns the value of named param from query string
     * 
     * nb if there is more than one param of same name, will return LAST ONE ONLY
     * 
     * @param string $param_name
     * @return string
     */
    function param_value($param_name){
        $qs = self::query_string();
        if(!empty($qs)){
            parse_str($qs, $params);
            $param_value = $params[$param_name];
        } else {
            $param_value = NULL;
        }
        
        return($param_value);
    }
}
