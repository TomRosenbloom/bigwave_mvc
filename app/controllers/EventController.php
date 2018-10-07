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
    /**
     * show events within given range of a given postcode NEWER VERSION
     *
     */
    public function show_in_range()
    {
        $event = $this->model($this->modelName);
        $data['events_arr'] = $event->getAll();
        $data['events_json'] = json_encode($data['events_arr']);

        if($this->getRequest()->getMethod() === 'POST') {
            $data = $this->getRequest()->getPostVars();
            $range = intval($data['range']);
            $postcode = $data['postcode']; // VALIDATION!!

            list($lat, $lng) = $event->postcode_lat_lng($postcode);

            $data['post_vars'] = $this->getRequest()->getPostVars();

            $data['events_arr'] = $event->events_in_circle($range, $lat, $lng);
            $data['events_json'] = json_encode($data['events_arr']);

        }

        $this->view('event/show_in_range', $data);
    }

    /**
     * show events within given range of a given postcode OLDER VERSION
     *
     * @return array an array of event details
     */
    public function show_all_in_range()
    {
        $this->view('event/range_form',$this->getRequest()->getPostVars());

        if($this->getRequest()->getMethod() === 'POST') {
            $data = $this->getRequest()->getPostVars();
            $range = intval($data['range']);
            $postcode = $data['postcode']; // VALIDATION!!

            $event = $this->model($this->modelName);

            list($lat, $lng) = $event->postcode_lat_lng($postcode);

            echo "<pre>";
            //var_dump($event->events_in_square(50, 52, 0)); // nb I see from this why PHPstorm has that feature that allows you to define in top of page comment block a local property as a class instance i.e. to make autocompletes work
            var_dump($event->events_in_circle($range, $lat, $lng));
            echo "</pre>";
        }
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
