<?php

//use App\LetsRideFeed;
//use App\Event;

/**
 * matters to do with reading the Lets Ride feed, AND the local API
 * (they should probably be in separate controllers)
 *
 */
class FeedController extends BaseController
{

    protected $modelName;

    /**
     * set model identifier in constructor
     */
    public function __construct()
    {
        $this->modelName = "Feed";
    }
    


    /**
     * refresh local db from Lets Ride feed
     * NB seems like the Let's Ride feed contains past events?
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
