<?php

class EventController extends BaseController
{

    protected $event;

    /**
     * instantiate model in constructor - good idea, or no?
     * I suppose the question is, do we necessarily want to instantiate an
     * event model for every use of EventController?
     * The answer to that is no - for e.g. in any case where we have a
     * static page connected with events
     * Maybe set the name of the model in the constructor and only use it to
     * create a model when necessary? Setting the name seems sensible as it
     * opens the way for various automations
     */
    public function __construct()
    {
        $this->event = $this->model("Event");
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
        // $event = new Event();
        $data['events_arr'] = $this->event->getAll();
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
