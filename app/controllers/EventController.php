<?php

class EventController extends BaseController
{

    protected $modelName;

    /**
     * set model identifier in constructor
     */
    public function __construct()
    {
        $this->modelName = "Event";
    }

    public function index()
    {
        $this->view('event/index','');
    }

    public function show_all_in_range()
    {

    }

    public function show_all()
    {
        $event = $this->model($this->modelName);
        $data['events_arr'] = $event->getAll();
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
