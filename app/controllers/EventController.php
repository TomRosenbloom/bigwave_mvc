<?php

class EventController extends BaseController
{
    public function index($id = '')
    {
        echo "index method of event controller<br>";

        $event = $this->model('Event');
        $event->getEvent($id);
    }
}
