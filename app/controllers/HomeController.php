<?php

class HomeController extends BaseController
{
    public function index()
    {
        $title = 'DIY MVC';
        $description = 'A homemade MVC framework';
        $this->view('home/index', compact('title', 'description')); 
    }
}
