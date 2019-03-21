<?php

//namespace App;

class UrlHelper
{
    function redirect($page){
        header('location: ' . URL_ROOT . '/' . $page);
    }

    function current(){
        $url_parts = explode('?',$_SERVER['REQUEST_URI']);
        return $url_parts[0];
    }
}
