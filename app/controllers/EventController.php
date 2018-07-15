<?php

class EventController extends BaseController
{
    public function index($id = '')
    {
        $event = $this->model('Event');
        $event->getEvent($id);

        $this->view('event/show', ['id'=>$id]);
    }
}
