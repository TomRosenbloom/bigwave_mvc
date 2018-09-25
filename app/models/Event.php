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

    public function getAllFromFeed()
    {
        // this is where we get the json feed data which we'll then hand off
        // to the controller which will supply it to the view
        // well no actually - that would be the case if we were using it for ajax
        // but as it's just to put in the db...?
        
    }
}
