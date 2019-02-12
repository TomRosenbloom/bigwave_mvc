<?php

//namespace App;

/*
 * so the idea here is to have a set up where I can use some composition 
 * - I'm thinkiing strategy pattern - to read external feeds
 * There are some basic operations that will apply to any feed, but the structure
 * of different feeds varies
 * We need a way of modelling the strucutre of a feed, of mapping that to the database,
 * and supplying that structure to some agnostic methods for processing the feed
 * 
 * Note this model will not extend BaseModel because it is not based on a database table
 * ...in fact I have - for now at least - extended BaseModel so as to have access to 
 * a database connection, which I need for the refresh method
 * Don't think this is right - but it works for now
 * 
 */

interface FeedInterface
{
    public function refresh();
}

// ok so every feed will use the same local db connection, but a different json url
// how do I deal with these two properties?


abstract class Feed extends BaseModel implements FeedInterface
{  
    protected $feedId;
    protected $jsonUrl;
    
    function __construct() 
    {       
        //$this->jsonUrl = $jsonUrl;
        parent::__construct('feeds');   
    }
    

}
