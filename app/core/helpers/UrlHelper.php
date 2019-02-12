<?php

//namespace App;

class UrlHelper
{
    function redirect($page){
        header('location: ' . URL_ROOT . '/' . $page);
    }

    function foo()
    {
        echo "foo";
    }
}
