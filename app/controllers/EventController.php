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

    public function vue_pagination()
    {
        $event = $this->model;
        
        $data['total'] = $event->getCount();
        
        // use paginator for initial view, showing page one only
        // all subsequent pagination will be done with js calls to API
//        $page = 1;
//        $range = 3;
//        $per_page = 5;
//        $paginator = new Paginator($page, $per_page, $range, $event->getCount());
        // pretty sure this isn't the way to do this - should just send to the initial view
        // the data that Vue will use to do an API call for first page...
        // ...but this is a good way to get the original view containing the first page of results - ?
        
        // the paginator links are to current page with query string...
        // ...but that isn't the way API pagination will work...
        // we need a variant of the paginator that doesn't have real links at all
        // i.e. just html with necessary bootstrap classes and Vue attributes
        
//        $data['paginator'] = $paginator;
        
//        $data['events_page'] = $event->getLimit($paginator->get_limit(), $paginator->get_offset());
//        $data['events_json'] = json_encode($data['events_page']);
                
        $this->view('event/vue_pagination', $data); 
    }
    
    /**
     * show all events, with a form for searching/filtering results
     */
    public function show_all_with_search_paginated()
    {
        $event = $this->model; // (pointless) assignment to change var name hence make code clearer      
        
        // send event data to view as json for map and array for list
        // ALL events for display before form submission
        // ... and in map (as opposed to paginated list)
        $data['events_arr'] = $this->model->getAll();
        
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

            // send post vars back to view for display in form
            $data['post_vars'] = $post_data;
  
            // if an origin feed was selected as a filter, get its name and return for display in the view
            $data['feed_name'] = $feed->getOneFromId($feed_id)['name'];
            
        }
        
        // pagination
        $page = UrlHelper::param_value('page') ?? 1;   
        $paginator = new Paginator($page, 5, 3, count($data['events_arr']));
        
        // paginate the results array
        $data['events_page'] = array_slice($data['events_arr'], $paginator->get_offset(), $paginator->get_limit());
        
        // send paginator object to view
        $data['paginator'] = $paginator;

        $data['events_json'] = json_encode($data['events_arr']);

        
        //$this->view('event/show_all_with_search', $data);
        $this->view('event/vue_test', $data);
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
