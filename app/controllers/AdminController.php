<?php

class AdminController extends BaseController
{
    public function index($param = '')
    {
        echo "index method of admin controller<br>";
        echo $param;

        $this->view('admin/index', []); 
    }
}
