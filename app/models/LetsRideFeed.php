<?php

//namespace App;

class LetsRideFeed extends Feed
{
    
    public function __construct()
    {
        $this->feedId = 1; // this is just temporary - I need a better way of having a unique
                           // identifier for each feed. Using the auto-increment table id is obvs bad idea
        
        parent::__construct();
        $feedDetails = $this->getOneFromId($this->feedId);
        $this->jsonUrl = $feedDetails['url'];
    }
    
    
    /**
     * refresh the local db copy of feed data
     * 
     * @return string
     */
    function refresh()
    {
        $json = file_get_contents($this->jsonUrl);
        $data = json_decode($json);

        // delete any previous events for this feed
        $this->discard();
        
        foreach($data->items as $item) {

            $name = $item->data->name;
            $description = iconv('UTF-8', 'ASCII//TRANSLIT', $item->data->programme->description); // need a general functiom to sanitise inputs
            //$description = iconv('UTF-8', 'ASCII//TRANSLIT', $item->data->description); 
            
            $date = $item->data->startDate;
            // nb there's a lot of location data, and not predicatable which is completed, if any
            // noticeable that ones that lack any other address/location data often have 'meeting point' entered
            // [is there any argument for having more than one table??]
            $location = $item->data->location->description ?? $item->data->location->address->addressLocality ?? $item->data->location->address->postcode ?? 'not known';
            $lat = $item->data->location->geo->latitude; // nb 'geo' also has 'type' which in current data is always 'GeoCoordinates'
            $long = $item->data->location->geo->longitude; // really, I need a schema showing what values there are, but haven't found taht...
            // thumbnail - there is nothing obvious to use as a thumbnail
            // perhaps logo? or something generic?
            $url = $item->data->url;

            try {
                $sql = 'INSERT INTO events (feed_id, title, description, event_date, latitude, longitude) VALUES(?,?,?,?,?,?)';
                $this->connection->prepare($sql)->execute([$this->feedId, $name, $description, $date, $lat, $long]);
            } catch(Exception $e) {
                echo 'ERROR: ' . $e->getMessage();
            }
        }   
        return "something";
    }     
}