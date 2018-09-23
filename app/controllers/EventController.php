<?php

class EventController extends BaseController
{
    public function index()
    {
        $this->view('event/index','');
    }

    public function show_one($id = '')
    {
        $event = $this->model('Event');
        $event->getEvent($id);

        $this->view('event/show', ['id'=>$id]);
    }
}
