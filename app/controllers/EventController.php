<?php

//namespace App;

class EventController extends DomainModelController
{
    /**
     * set model identifier
     */
    public function __construct()
    {
        parent::__construct("Event"); // have I used 'value object' here?
    }

    public function index()
    {
        $this->view('event/index','');
    }
    
    /**
     * show all events, with a form for searching/filtering results
     */
    public function show_all_with_search()
    {
        $event = $this->model; // (pointless) assignment to change var name hence make code clearer
        
        // send event data to view as json for map and array for list
        // ALL events for display before form submission
        $data['events_arr'] = $this->model->getAll();
        $data['events_json'] = json_encode($data['events_arr']);
        
        // send list of event feeds (aka source, type) for select
        $feed = new Feed;
        $data['feeds'] = $feed->getAll();

        if($this->getRequest()->getMethod() === 'POST') {
            $post_data = $this->getRequest()->getPostVars();
            $rangeKm = intval($post_data['range']);
            $postcode = $post_data['postcode']; // VALIDATION!!
            $feed_id = $post_data['feed'];
            $clear = $post_data['clear'] ?? null;

            if($clear){
                $data['events_arr'] = $this->model->getAll();
                $post_data = [];
            } else {
                $postcodeRange = new PostcodeRange($postcode, $rangeKm);
                $data['events_arr'] = $event->getFiltered($postcodeRange, $feed_id);                    
            }
            
            $data['events_json'] = json_encode($data['events_arr']);

            // send post vars back to view for display in form
            $data['post_vars'] = $post_data;
            
            // if an origin feed was selected as a filter, get its name and return for display in the view
            $data['feed_name'] = $feed->getOneFromId($feed_id)['name'];
            
        }

        //$this->view('event/show_all_with_search', $data);
        $this->view('event/vue_test', $data);
    }


    /**
     * show all the events
     */
    public function show_all()
    {
        $event = $this->model;
        $data['events_arr'] = $event->getAll();
        $data['events_json'] = json_encode($data['events_arr']);

        $this->view('event/show_all', $data);
    }

    
    public function show_all_paginated()
    {
        $event = $this->model;
        
        $page = UrlHelper::param_value('page');        
        
        $paginator = new Paginator($page, 5, 3, $event->getCount());
                
        $data['events_arr'] = $event->getLimit($paginator->get_limit(), $paginator->get_offset());
        $data['events_json'] = json_encode($data['events_arr']);
        
        $data['paginator'] = $paginator;
        
        

        var_dump($paginator->links_array());
        
        $this->view('event/show_all_paginated', $data);        
    }

    /**
     * show just one event
     * 
     * @param int $id
     */
    public function show_one($id = '')
    {
        $event = new Event();
        $id = $event->getFirstId(); // temp: for proof of concept
        $data = $event->getOneFromId($id);

        $this->view('event/show_one', $data);
    }
}
