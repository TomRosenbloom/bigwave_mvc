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
            $rangeKm = intval($post_data['range']);
            $postcode = $post_data['postcode']; // VALIDATION!!
            $feed_id = $post_data['feed'];
            $clear = $post_data['clear'] ?? null;

            $postcodeRange = new PostcodeRange($postcode, $rangeKm);
            $data['events_arr'] = $event->getFiltered($postcodeRange, $feed_id);
            $data['events_json'] = json_encode($data['events_arr']);
            
//            // just dealing with feed filter...
//            if(!empty($feed_id)){
//                
//                $data['events_arr'] = $event->getWhereWithJoin(array(
//                    array('name'=>'feed_id', 'comparison'=>'=', 'value'=>$feed_id)
//                ));
//                $data['events_json'] = json_encode($data['events_arr']); 
////                echo "<pre>"; var_dump($data['events_arr']); echo "</pre>";
//            }

            
            // how to do filtering on both range and feed?
            // this is a bit tricky - the existing code for doing a range search
            // uses getWhereWithJoin within the model, but in the new code for
            // filtering on feed we are doing it more directly in this controller
            // so I can't combine these two into one query - that would be v confusing anyway
            // I want the intersection of two queries
            // A single use of getWhereWithJoin that I send all the params through to?
            // A use of getWhereWithJoin with 'post facto' filtering?
            
            // or, wrap up the range related stuff in a single filtering function
            // currently I'm doing two separate stages in this controller, ie
            // 1. get lat long of postcode
            // 2. call , but I should 
            // better wrap them in a single function $event->events_in_circle($range, $lat, $lng)
            // The reason I did it this way is it's more obvious how to have default values
            // but in fact that should be easy enough to deal with in wrapper function ('service'?)
            // i.e. in case postcode/range are not set
            
            // There's a general question here of how to deal with multiple filters
            
            
            

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

            // send post vars back to view for display in form
            $data['post_vars'] = $post_data;
            
            // if an origin feed was selected as a filter, get its name and return for display in the view
            $data['feed_name'] = $feed->getOneFromId($feed_id)['name'];
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
