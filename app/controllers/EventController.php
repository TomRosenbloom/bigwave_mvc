<?php

//namespace App;

class EventController extends BaseController
{
    /**
     * set model identifier in constructor
     */
    public function __construct()
    {
        $this->modelName = "Event";
        parent::__construct();
    }

    public function index()
    {
        $this->view('event/index','');
    }
    
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
            $range = intval($post_data['range']);
            $postcode = $post_data['postcode']; // VALIDATION!!
            $originFeed = $post_data['feed'];
            $clear = $post_data['clear'] ?? null;
            
            
            // quick bodge
            if(!empty($originFeed)){
                //echo $originFeed;
                $data['events_arr'] = $event->getWhereWithJoin(array(
//                  array('name'=>'latitude', 'comparison'=>'<=', 'value'=>$north_lat),
//                  array('name'=>'latitude', 'comparison'=>'>=', 'value'=>$south_lat),
//                  array('name'=>'longitude', 'comparison'=>'<=', 'value'=>$east_long),
//                  array('name'=>'longitude', 'comparison'=>'>=', 'value'=>$west_long),
                    array('name'=>'feed_id', 'comparison'=>'=', 'value'=>$originFeed)
                ));
                $data['events_json'] = json_encode($data['events_arr']);
            }


//            // if the Clear button was pressed, or we don't have both postcode and range, use default values - to show all events
//            // In addition - and prior - to this there needs to be form validation
//            if(!($postcode and $range) or $clear){ // no post data or form cleared, set to defaults
//                $lat = 52;
//                $lng = 0;
//                $range = 999;
//                $post_data = array('postcode'=>null,'range'=>null);
//            } else { // use entered postcode to get lat and lng for range origin
//                list($lat, $lng) = $event->postcode_lat_lng($postcode);
//            }
//
//            // send post vars back to view for display in form
//            $data['post_vars'] = $post_data;
//
//            // send event data to form as json for map and array for list
//            $data['events_arr'] = $event->events_in_circle($range, $lat, $lng);
//            $data['events_json'] = json_encode($data['events_arr']);
//
//            // confirmation message
//            // create a flash messaging component?
//            $data['message'] = count($data['events_arr']) . ' events';

        }

        $this->view('event/show_all_with_search', $data);   
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
