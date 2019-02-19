<?php

class BritishTriathlonFeed extends ExternalFeed
{
    
    public function __construct()
    {
        $this->feedId = 3; 
        parent::__construct();
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

        // insert data into events table from feed
        foreach($data->items as $item) {
            if($item->state != 'deleted'){
                $name = $item->data->name;
                $description = iconv('UTF-8', 'ASCII//TRANSLIT', $item->data->description); 
                $date = $item->data->startDate;    
                $location = $item->data->location->description ?? $item->data->location->address->addressLocality ?? $item->data->location->address->postcode ?? 'not known';
                $lat = $item->data->location->geo->latitude; 
                $long = $item->data->location->geo->longitude; 
                $url = $item->data->url;            
            }
            
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