<?php

//namespace App;
//use \BaseModel;

class PostcodeRange
{
    private $postcode;
    private $rangeKm;
    
    public function __construct(string $postcode, int $rangeKm)
    {
        $this->setPostcode($postcode);
        $this->setRangeKm($rangeKm); 
    }
    
    function getPostcode() {
        return $this->postcode;
    }

    function getRangeKm() {
        return $this->rangeKm;
    }

    function setPostcode($postcode) {
        // validate postcode here?
        
        $this->postcode = $postcode;
    }

    function setRangeKm($rangeKm) {
        $this->rangeKm = $rangeKm;
    }


}

class Event extends BaseModel
{
    /**
     *
     * @var type 
     */
    private $parentTable;
    private $foreignKey;
    private $parentTableNameField;
    private $parentTableNameFieldAlias;
    private $parentTableFields;

        
    public function __construct()
    {
        parent::__construct('events');
        
        $this->setParentTable('feeds');
        $this->setForeignKey('feed_id');
        $this->setParentTableNameField('name');
        $this->setParentTableNameFieldAlias();
        $this->setParentTableFields(array('name')); // not currently using this - the idea is to be able to retrieve not just the name field
                                                    // should be an array of names and aliases - or the aliases are generated auto
    }

    
    /**
     * return array of events filtered by postcode range and origin feed
     * 
     * 
     * @param PostcodeRange $range
     * @param int $feed_id
     * @return type
     */
    public function getFiltered(PostcodeRange $range, int $feed_id)
    {
        $filterResults = [];
        if(isset($feed_id))
        {
            $filteredByFeed = $this->getWhereWithJoin(array(
                array('name'=>'feed_id', 'comparison'=>'=', 'value'=>$feed_id)
            ));
            $filterResults['filteredByFeed'] = array_map('serialize', $filteredByFeed);
            
        }
        if(isset($range)) {
            $postcode = $range->getPostcode();
            $rangeKm = $range->getRangeKm();
            $filteredByRange = $this->getEventsInRange($postcode, $rangeKm);
            $filterResults['filteredByRange'] = array_map('serialize', $filteredByRange);
        }
        
        // https://stackoverflow.com/a/31411350

        return array_map('unserialize', call_user_func_array('array_intersect', $filterResults));

    }
    
    /**
     * create alias for field name in database queries according to prescribed formula:
     * tableName_fieldName
     * 
     * @param string $tableName
     * @param string $fieldName
     * @return string
     */
    protected function createFieldNameAlias(string $tableName, string $fieldName)
    {
        return substr($tableName, 0, strlen($tableName)-1) . '_' . $fieldName;
    }
    
    /**
     * do a db query that 
     * 1. joins with a table related via foreign key
     * 2. has a where clause
     * 
     * @param array $paramTuples
     * @return array
     */
    public function getWhereWithJoin(array $paramTuples)
    {
        // add fields to SELECT clause
        $query = 'SELECT *, ' . $this->parentTable . '.' . $this->parentTableNameField . ' ';
        $query .= 'AS ' . $this->parentTableNameFieldAlias . ' ';
        $query .= 'FROM ' . $this->table . ' ';
        
        // make a join
        $query .= 'JOIN ' . $this->parentTable . ' ';
        $query .= 'ON ' . $this->table . '.' . $this->foreignKey . ' = ' . $this->parentTable . '.id ';
        
        // make a where clause from params
        $paramVals = [];
        if(count($paramTuples) > 0){
            $where = ' WHERE ';
            foreach($paramTuples as $paramTuple){
                $name = $paramTuple['name'];
                $value = $paramTuple['value'];
                $comparison = $paramTuple['comparison'];
                $paramVals[] = $value;
                $where .= ' ' . $name . ' ' . $comparison . ' ? AND ';
            }
            $where = substr($where, 0, strlen($where) - 4);
        }
        
        // stick the parts together
        $query .= $where;

        //print_r(DebugHelper::interpolateQuery($query, $paramVals));
        
        try{
            $stmt = $this->connection->prepare($query);
            $stmt->execute($paramVals);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            echo 'ERROR: ' . $e->getMessage();
        }

        return $data;       
    }    

    
    /**
     * Override BaseModel->getAll
     * ...in order to do a table join and get the feed name via foreign key
     * This is how I'll have to do it for now in the absence of any kind of ORM
     * ...and will have to do same for other methods getWhere and ...
     * 
     * @return type
     */
    public function getAll()
    {
        $parentTable = 'feeds';
        $foreignKey = 'feed_id';
        $parentTableNameField = 'name';
        $parentTableNameFieldAlias = substr($parentTable, 0, strlen($parentTable)-1) . '_' . $parentTableNameField;
        
        $sql = 'SELECT *, ' 
                . $parentTable . '.' . $parentTableNameField
                . ' AS ' .  $parentTableNameFieldAlias 
                . ' FROM ' . $this->table
                . ' JOIN ' . $parentTable 
                . ' ON ' 
                . $this->table . '.' . $foreignKey 
                . ' = '
                . $parentTable . '.id';
        
        try{
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            echo 'ERROR: ' . $e->getMessage();
        }

        return $data;
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


    public function getEventsInRange(string $postcode, int $rangeKm)
    {
        if(!empty($postcode) && !empty($rangeKm)){
            list($lat, $lng) = $this->postcode_lat_lng($postcode);
        } else {
            $lat = 52; // set these defaults elsewhere as constants
            $lng = 0;
            $rangeKm = 999;
        }
        $events_arr = $this->events_in_circle($rangeKm, $lat, $lng);
        return $events_arr;
    }
    
    
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

        return $this->getWhereWithJoin(array(
            array('name'=>'latitude', 'comparison'=>'<=', 'value'=>$north_lat),
            array('name'=>'latitude', 'comparison'=>'>=', 'value'=>$south_lat),
            array('name'=>'longitude', 'comparison'=>'<=', 'value'=>$east_long),
            array('name'=>'longitude', 'comparison'=>'>=', 'value'=>$west_long),
            ));
    }

    public function events_in_circle(int $radius, float $origin_lat, float $origin_long)
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
    
    function getParentTable() {
        return $this->parentTable;
    }

    function getForeignKey() {
        return $this->foreignKey;
    }

    function getParentTableNameField() {
        return $this->parentTableNameField;
    }

    function getParentTableNameFieldAlias() {
        return $this->parentTableNameFieldAlias;
    }

    function getParentTableFields() {
        return $this->parentTableFields;
    }

    function setParentTable(string $parentTable) {
        $this->parentTable = $parentTable;
    }

    function setForeignKey(string $foreignKey) {
        $this->foreignKey = $foreignKey;
    }

    function setParentTableNameField(string $parentTableNameField) {
        $this->parentTableNameField = $parentTableNameField;
    }

    function setParentTableNameFieldAlias() {
        $this->parentTableNameFieldAlias = $this->createFieldNameAlias($this->parentTable, $this->parentTableNameField);
        //$this->parentTableNameFieldAlias = $this->parentTableFields = substr($this->parentTable, 0, strlen($this->parentTable)-1) . '_' . $this->parentTableNameField;
    }

    /**
     * To specify which fields to retrieve from the parent table in a foreign key rel
     * 
     * @param array $parentTableFields
     */
    function setParentTableFields(array $parentTableFields) {
        $this->parentTableFields = $parentTableFields;
    }    
    
}
