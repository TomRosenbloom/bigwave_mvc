<?php

use core\Config; // don't actually need to do this - in this little system no reason not to just use global namespace

class FeedController extends BaseController
{
    public function read()
    {
        $config = new Config;

        $db = Database::getInstance($config);
        try {
            $connection = $db->getConnection();
        } catch(PDOException $e) {
            echo $e->getMessage();
        }

        // $test = $connection->query('SELECT * FROM events'); // connection is null

        $jsonUrl = $config->JSON_URL;
        $json = file_get_contents($jsonUrl);
        $data = json_decode($json);

        $connection->query('TRUNCATE TABLE events')->execute(); // danger

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

            echo 'name: ', $name, '<br>';
            echo 'description: ', $description, '<br>';
            echo 'date: ', $date, '<br>';
            echo 'location: ', $location, '<br>';
            echo 'lat: ', $url, '<br>';
            echo 'long: ', $url, '<br>';
            echo 'thumbnail: ', '<br>';
            echo 'url: ', $url, '<br>';
            echo '<br>';

            try {
                $sql = 'INSERT INTO events (title, description) VALUES(?,?)';
                $connection->prepare($sql)->execute([$name, $description]);
            } catch(Exception $e) {
                echo 'ERROR: ' . $e->getMessage();
            }

        }
    }
}
