<?php

use core\Config;

/**
 * now it's debatable wether having a base model class is right or good,
 * but I'll go with it for now for speed
 * also for time being will couple this to mysql db...
 *
 * @var [type]
 */
abstract class BaseModel
{
    protected $connection;
    protected $config;
    protected $table;

    public function __construct($table)
    {
        $this->table = $table;
        $this->connection = $this->dbConnect(); //// sure? Does every model method need a database connection?
    }


    // make database connection
    // [should this *return* a connection?]
    public function dbConnect()
    {
        $db = Database::getInstance($this->config);
        try {
            $connection = $db->getConnection();
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
        return $connection;
    }

    
    // should make the PDO fetch method an option?
    // 
    // should make these methods static, to avoid always having to instantiate object?
    // but that messes up existing usage...
    
    public function getOneFromId($id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ?';
        $params = [$id];
        
        //echo DebugHelper::interpolateQuery($query, $params);
        
        try{
            $stmt = $this->connection->prepare('SELECT * FROM ' . $this->table . ' WHERE id = ?');
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC); 
        } catch(PDOException $e){
            echo 'ERROR: ' . $e->getMessage();
        }

        return $data;
    }

    public function getAll()
    {
        try{
            $stmt = $this->connection->prepare('SELECT * FROM ' . $this->table);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            echo 'ERROR: ' . $e->getMessage();
        }

        return $data;
    }

    public function getAllWhere(array $paramTuples)
    {
        // make a where clause from params
        // nb this would be easier to do fluent style because can just use andwhere
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

        try{
            $stmt = $this->connection->prepare('SELECT * FROM ' . $this->table . $where);
            $stmt->execute($paramVals);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            echo 'ERROR: ' . $e->getMessage();
        }

        return $data;
    }

    /**
     * execute a query passed as string, using params if required
     * NB this will require the query to be supplied in correct way wrt prepared query syntax
     * ...should add some checking of that
     * 
     * @param type $query
     * @param type $params
     * @return array
     */
    public function doQuery($query, $params)
    {
        // execute a query passed in as string
        // Traversy puts basic database query operations in his database class
        // i.e. make query, prepare statement, bind values, execute, which is a good approach I think
        // Also he has the methods I have above for getting all, getting where, count rows, etc in that
        // database class, not in a base model class like I'm doing here
        // Will be interesting to see if he has a base model later on and if so what he has in it
        
        try{
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            echo 'ERROR: ' . $e->getMessage();
        }

        return $data;        
    }

    /**
     * get first id from table
     * 
     * @return string the id number
     */
    public function getFirstId()
    {
        $sql = 'SELECT MIN(id) AS id FROM ' . $this->table;
        $result = $this->doQuery($sql, []);
        return $result[0]['id'];
    }
    
    
    public function getCount()
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->table;
        $count = $this->connection->query($sql)->fetchColumn();
        return $count;        
    }
}
