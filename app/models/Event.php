<?php

class Event
{
    public function getEvent($id)
    {
        //echo "get event with id " . $id . "<br>";
        // ok, so the way this works is we periodically update our local database
        // from the json feed and use the local database to get and display results
        // so the first thing I need to write is the thing that updates the local db from the feed
    }
}
