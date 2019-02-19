<?php

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

/**
 * why an interface? Because - for e.g. - every feed needs to refresh, but how that
 * is done will vary
 * On that basis, it's debatable whether I should be including the discard method
 * since that is always done in the same way and is defined in detail in the implementing class
 */
interface FeedInterface
{
    /**
     * read the feed and return json
     */
    public function readToArray();
    
    /**
     * refresh the local database with data from a remote feed
     */
    public function refresh();
    
    /**
     * delete local data previously imported from a remote feed
     */
    public function discard();
}

abstract class ExternalFeed extends Feed implements FeedInterface
{
    public $jsonUrl;
    public $feedId;
    
    public function __construct()
    {
        parent::__construct();
        echo $this->feedId;
        $feedDetails = $this->getOneFromId($this->feedId);
        $this->jsonUrl = $feedDetails['url'];
    }
    
    public function readToArray()
    {
        $json = file_get_contents($this->jsonUrl);
        $data = json_decode($json);
        return $data;
    }
        
    public function discard()
    {
        // delete any previous events for this feed
        $sth = $this->connection->prepare('DELETE FROM events WHERE feed_id = :feed_id');
        $sth->bindParam(':feed_id', $this->feedId, PDO::PARAM_INT);
        $sth->execute();        
    }
}

