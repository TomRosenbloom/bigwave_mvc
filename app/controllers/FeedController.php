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
        $json = file_get_contents($feed->jsonUrl);
        $json = json_decode($json, JSON_PRETTY_PRINT);
        $this->view('feed/readAll', ['json'=>$json]);        
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
