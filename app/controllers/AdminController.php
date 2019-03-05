<?php

class AdminController extends BaseController
{

    public function index($param = '')
    {
        // echo "index method of admin controller<br>";
        // echo $param;

        $this->view('admin/index', []);
    }
    
    public function test()
    {
        $this->view('admin/test', []);
    }
}
