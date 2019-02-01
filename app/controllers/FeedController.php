<?php

use App\LetsRideFeed;

/**
 * matters to do with reading the Lets Ride feed, AND the local API
 * (they should probably be in separate controllers)
 *
 * NB should not be doing db queries in a controller...
 */
class FeedController extends BaseController
{

    protected $connection;
    protected $jsonUrl;

    public function __construct()
    {
        $db = Database::getInstance();
        try {
            $this->connection = $db->getConnection();
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
        $this->jsonUrl = JSON_URL;
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
     * More importantly, SHOULD NOT be doing all of the below in a controller!!!
     *
     * @return [type] [description]
     */
    public function refresh()
    {
        $feed = new LetsRideFeed($this->jsonUrl, $this->connection);
        $feed->refresh();
                
        $this->view('feed/refresh', []);
    }
}
