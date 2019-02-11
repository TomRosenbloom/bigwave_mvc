<?php

namespace App;

class LetsRideFeed extends Feed
{
    
    public function __construct()
    {
        $this->jsonUrl = JSON_URL;
        parent::__construct($this->jsonUrl);
    }    
    
    function refresh()
    {
        $json = file_get_contents($this->jsonUrl);
        $data = json_decode($json);

        $this->connection->query('TRUNCATE TABLE events')->execute(); // danger

        //echo "<pre>"; var_dump($data); echo "</pre>";
        
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
                $sql = 'INSERT INTO events (title, description, event_date, latitude, longitude) VALUES(?,?,?,?,?)';
                $this->connection->prepare($sql)->execute([$name, $description, $date, $lat, $long]);
            } catch(Exception $e) {
                echo 'ERROR: ' . $e->getMessage();
            }
        }   
        return "something";
    }     
}