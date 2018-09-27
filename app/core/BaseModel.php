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
        $this->config = new Config;
        $this->table = $table;
        $this->dbConnect();
    }

    public function dbConnect()
    {
        $db = Database::getInstance($this->config);
        try {
            $this->connection = $db->getConnection();
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function doQuery($query, $params)
    {

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

}
