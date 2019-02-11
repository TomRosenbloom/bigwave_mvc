<?php

namespace App;
use \BaseModel;

class Event extends BaseModel
{
    public function __construct()
    {
        parent::__construct('events');
    }

    // find events within x km of a given postcode
    // https://www.mullie.eu/geographic-searches/
    // here in the model we use the form inputs collected by the controller,
    // then do the necessary calculations and queries and hand the results
    // back to the controller
    // 1. make a function for calculating the distance between two lat/long coords - to be used later
    // 2. make a function that gets events located in a square around given lat/lng
    //    - this is an easy db query as it uses just greater/less than
    // 3. make a function that extracts just the events in a circle around given lat/lng
    //    - uses 1. to test the results of 2. and reject ones too far from centre
    //
    //    Oh and will need a function to get lat/lng from postcode
    //    ...will put that in here for noow, but doesn't strictly belong (need a helpers folder?)


    /**
     * return the distance between two locations
     * - really this belongs somewhere else as it is a general utility
     *
     * @param  float $lat1
     * @param  float $lng1
     * @param  float $lat2
     * @param  float $lng2
     * @return float distance in km
     */
    public function distance($lat1, $lng1, $lat2, $lng2)
    {
        // https://www.mullie.eu/geographic-searches/

        // convert latitude/longitude degrees for both coordinates
        // to radians: radian = degree * π / 180
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        # calculate great-circle distance
        $distance = acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lng1 - $lng2));

        # distance in human-readable format:
        # earth's radius in km = ~6371
        return 6371 * $distance;
    }

    public function events_in_square(int $distance, float $origin_lat, float $origin_long)
    {
        $radius = 6371;
        $north_lat = $origin_lat + rad2deg($distance/$radius);
        $south_lat = $origin_lat - rad2deg($distance/$radius);
        $east_long = $origin_long + rad2deg($distance/$radius/cos(deg2rad($origin_lat)));
        $west_long = $origin_long - rad2deg($distance/$radius/cos(deg2rad($origin_lat)));

        return $this->getAllWhere(array(
            array('name'=>'latitude', 'comparison'=>'<=', 'value'=>$north_lat),
            array('name'=>'latitude', 'comparison'=>'>=', 'value'=>$south_lat),
            array('name'=>'longitude', 'comparison'=>'<=', 'value'=>$east_long),
            array('name'=>'longitude', 'comparison'=>'>=', 'value'=>$west_long),
            ));
    }

    public function events_in_circle($radius, $origin_lat, $origin_long)
    {
        $events = [];
        $eventsInSquare = $this->events_in_square($radius, $origin_lat, $origin_long);
        foreach($eventsInSquare as $event){
            if($this->distance($event['latitude'], $event['longitude'], $origin_lat, $origin_long) <= $radius){
                // $events[$event['id']] = $event['title'];
                $events[] = $event;
            }
        }
        return $events;
    }

    public function postcode_lat_lng($postcode)
    {
        $postcode_lookup_url = 'http://api.postcodes.io/postcodes/' . $postcode;
        $json = file_get_contents($postcode_lookup_url);
        $data = json_decode($json, true);
        return array($data['result']['latitude'],$data['result']['longitude']);
    }
}
