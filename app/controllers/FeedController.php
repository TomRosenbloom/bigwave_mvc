<?php

use core\Config;

/**
 * matters to do with reading the Lets Ride feed, AND the local API
 * (they should probably be in separate controllers)
 *
 * NB should not be doing db queries in a controller...
 */
class FeedController extends BaseController
{

    protected $connection;
    protected $config;

    public function __construct()
    {
        $this->config = new Config;

        $db = Database::getInstance($this->config);
        try {
            $this->connection = $db->getConnection();
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * get all event data from db and dump as json
     *
     * @return [type] [description]
     */
    public function readAll()
    {
        $data = $this->connection->query('SELECT * FROM events')->fetchAll();
        var_dump(json_encode($data));
    }

    /**
     * get one event from db and display as json, using a view
     *
     * @return [type] [description]
     */
    public function readOne()
    {
        $id = 1;
        try{
            // $data = $this->connection
            //     ->prepare('SELECT * FROM events WHERE id = ?')
            //     ->execute([$id])
            //     ->fetch();

            $stmt = $this->connection->prepare('SELECT * FROM events WHERE id = ?');
            $stmt->execute([$id]);
            $data = $stmt->fetch();
        } catch(PDOException $e){
            echo 'ERROR: ' . $e->getMessage();
        }
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
        $jsonUrl = $this->config->JSON_URL;
        $json = file_get_contents($jsonUrl);
        $data = json_decode($json);

        $this->connection->query('TRUNCATE TABLE events')->execute(); // danger

        foreach($data->items as $item) {

            $name = $item->data->name;
            $description = iconv('UTF-8', 'ASCII//TRANSLIT', $item->data->programme->description); // need a general functiom to sanitise inputs
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

        $this->view('feed/refresh', []);
    }
}
