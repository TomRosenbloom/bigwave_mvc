<?php

class EventController extends BaseController
{
    public function index()
    {
        $this->view('event/index','');
    }

    public function show_all()
    {
        $event = new Event();
        $events = $event->getAll();

        $this->view('event/show_all', $events); // doesn't matter what you call it here, it will be $data in the view
    }

    public function show_one($id = '')
    {
        $id = 1;
        $event = new Event();
        $data = $event->getOneFromId($id);

        $this->view('event/show_one', $data);
    }
}
