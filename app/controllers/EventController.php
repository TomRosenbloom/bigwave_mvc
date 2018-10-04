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
        $data['events_arr'] = $event->getAll();
        $data['events_json'] = json_encode($event->getAll());

        $this->view('event/show_all', $data);
    }

    public function show_one($id = '')
    {
        $id = 1;
        $event = new Event();
        $data = $event->getOneFromId($id);

        $this->view('event/show_one', $data);
    }
}
