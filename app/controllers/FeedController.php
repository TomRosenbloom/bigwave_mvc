<?php

use App\LetsRideFeed;

/**
 * matters to do with reading the Lets Ride feed, AND the local API
 * (they should probably be in separate controllers)
 *
 * NB should not be doing db queries in a controller...
 */
class FeedController extends BaseController
{

    /**
     * get all event data from db and dump as json
     *
     * @return [type] [description]
     */
    public function readAll()
    {
        $event = new Event();
        $data = $event->getAll();
        var_dump(json_encode($data));
    }

    /**
     * get one event from db and display as json, using a view
     *
     * @return [type] [description]
     */
    public function readOne($id = '')
    {        
        $event = new Event();
        $data = $event->getOneFromId($id);
        $json = json_encode($data);
        $this->view('feed/readOne', ['json'=>$json]);
    }

    /**
     * refresh local db from Lets Ride feed
     * NB seems like the feed contains past events?
     * That's ok. But something else wrong with dates - mostly 2500-01-01...
     * (no future events with valid dates...)
     *
     * @return [type] [description]
     */
    public function refresh()
    {
        $feed = new LetsRideFeed();
        $feed->refresh();
                
        $this->view('feed/refresh', []);
    }
}
