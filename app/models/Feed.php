<?php

namespace App;

/*
 * so the idea here is to have a set up where I can use some composition 
 * - I'm thinkiing strategy pattern - to read external feeds
 * There are some basic operations that will apply to any feed, but the structure
 * of different feeds varies
 * We need a way of modelling the strucutre of a feed, of mapping that to the database,
 * and supplying that structure to some agnostic methods for processing the feed
 * 
 * Note this model will not extend BaseModel because it is not based on a database table
 * 
 * how will I do composition when (if) it's one class per file? Is that a problem?
 * For composition I will want an abstract Feed class (or interface), plus...
 * 
 */

abstract class Feed
{  
    protected $jsonUrl;
    protected $connection;
    
    function __construct($jsonUrl, $connection) {
        $this->jsonUrl = $jsonUrl;
        $this->connection = $connection;
    }
    

}
