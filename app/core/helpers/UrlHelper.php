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
    
    function query_string(){
        return(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY));
    }
    
    function param_value($param_name){
        $qs = self::query_string();
        parse_str($qs, $params);
        return($params[$param_name]); // nb if there is more than one param of same name, will return LAST ONE
    }
}
