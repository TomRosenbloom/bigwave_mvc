<?php

//namespace App;

class Feed extends BaseModel
//class Feed extends BaseModel implements FeedInterface
{  
    protected $feedId;
    
    function __construct() 
    {       
        //$this->jsonUrl = $jsonUrl;
        parent::__construct('feeds');   
    }

}
