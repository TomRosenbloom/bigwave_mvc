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

    public function read($feedName)
    {
        $feedModelName = $feedName . 'Feed';
        $feed = new $feedModelName;
        $jsonArr = $feed->readToArray();
        $this->view('feed/read', ['json'=>$jsonArr]);        
    }
    
    /**
     * refresh local db from feed
     *
     * @return [type] [description]
     */
    public function refresh($feedName)
    {
        $feedModelName = $feedName . 'Feed';
        $feed = new $feedModelName;
        $feed->refresh();
                
        $this->view('feed/refresh', []);
    }
}
