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
        $data['events_arr'] = $event->getAllWhere(array(
            array(
            'name' => 'event_date',
            'comparison' => '>',
            'value' => date('Y-m-d H:i:s')// MySQL-specific!!
            )));
        $data['events_json'] = json_encode($data['events_arr']);

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
