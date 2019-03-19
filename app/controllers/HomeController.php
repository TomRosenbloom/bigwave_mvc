<?php

class HomeController extends BaseController
{
    public function index()
    {
        $title = 'DIY MVC';
        $description = 'A homemade MVC framework';
        $this->view('home/index', compact('title', 'description'));
    }
    
    public function vue_play()
    {
        $this->view('home/vue_play', ['title'=>'Vue play', 'description'=>'a page for trying out some Vue stuff']);
    }
}
