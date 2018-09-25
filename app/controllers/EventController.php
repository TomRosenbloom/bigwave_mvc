<?php

class EventController extends BaseController
{
    public function index()
    {
        $this->view('event/index','');
    }

    public function show_all()
    {

    }

    public function show_one($id = '')
    {
        $id = 1;
        $event = new Event();
        $data = $event->getOneFromId($id);

        // echo "<pre>"; var_dump($data); echo "</pre>";

        $this->view('event/show_one', ['data'=>$data]);
    }
}
