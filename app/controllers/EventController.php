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
        // so in the controller, we just need to do the organisational stuff
        // create a form to get the post code and range, then hand the form inputs
        // to the model to get back array of events in range and hand these back to view
        $event = $this->model($this->modelName);
        echo "<pre>";
        //var_dump($event->events_in_square(50, 52, 0)); // nb I see from this why PHPstorm has that feature that allows you to define in top of page comment block a local property as a class instance i.e. to make autocompletes work
        var_dump($event->events_in_circle(50, 52, 0));
        echo "</pre>";
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
