<?php

class BritishTriathlonFeed extends Feed
{
    
    public function __construct()
    {
        $this->feedId = 3; // this is just temporary - I need a better way of having a unique
                           // identifier for each feed. Using the auto-increment table id is obvs bad idea
        
        parent::__construct();
        $feedDetails = $this->getOneFromId($this->feedId);
        $this->jsonUrl = $feedDetails['url'];
    }


/**
 * Replaces any parameter placeholders in a query with the value of that
 * parameter. Useful for debugging. Assumes anonymous parameters from 
 * $params are are in the same order as specified in $query
 *
 * @param string $query The sql query with parameter placeholders
 * @param array $params The array of substitution parameters
 * @return string The interpolated query
 */
public static function interpolateQuery($query, $params) {
    $keys = array();

    # build a regular expression for each parameter
    foreach ($params as $key => $value) {
        if (is_string($key)) {
            $keys[] = '/:'.$key.'/';
        } else {
            $keys[] = '/[?]/';
        }
    }

    $query = preg_replace($keys, $params, $query, 1, $count);

    #trigger_error('replaced '.$count.' keys');

    return $query;
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
        $sth = $this->connection->prepare('DELETE FROM events WHERE feed_id = :feed_id');
        $sth->bindParam(':feed_id', $this->feedId, PDO::PARAM_INT);
        $sth->execute();

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