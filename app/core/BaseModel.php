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

    protected $validation_rules;

    public function __construct($table)
    {
        $this->config = new Config; // don't like this - this is like creating a global. Only justified if every method uses config - ?
        $this->table = $table;
        $this->dbConnect();
    }

    // make database connection
    // [should this *return* a connection?]
    public function dbConnect()
    {
        $db = Database::getInstance($this->config);
        try {
            $this->connection = $db->getConnection();
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getOneFromId($id)
    {
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

    public function doQuery($query, $params)
    {
        // execute a query passed in as string
    }

}
