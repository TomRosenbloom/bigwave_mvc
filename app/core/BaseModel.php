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

    protected $validation_rules = array(); //// I want setting of validation rules to be enforced,
                                           //// but I don't want to make this entire class an interface...
    protected $validation_errors = array();

    public function __construct($table)
    {
        $this->table = $table;
        $this->dbConnect(); //// sure? Does every model method need a database connection?
    }

    /**
     * validate incoming data for adding/updating an object by comparing against validation rules for object
     * returns boolean, to get any errors use getValidationErrors
     *
     * @param  array   $data incoming data, most likely POST data
     * @return boolean            [description]
     */
    public function isValid(array $data)
    {

        $this->validation_errors['test'] = $this->validatePropertyDynamic('test', 'Test Name', array(6,8));

        foreach($data as $name => $value){
            if(isset($this->validation_rules[$name])){
                $this->validation_errors[$name] = $this->validateProperty($name, $value);
            }
        }
        if(count($this->validation_errors) === 0){
            return true;
            //// so this returns true/false
            //// to get any errors, I just need a getter for validation_errors
            //// NB if there are no validation rules set, then validation will
            //// pass - fair enough I suppose?
        }
    }


    /**
     * return array of validation errors
     *
     * @return array validation errors
     */
    public function getValidationErrors()
    {
        return $this->validation_errors;
    }

    /**
     * validate an object property
     * returns array of errors
     *
     * @param  string $name  name of the property
     * @param  [type] $value value to be validated
     * @return array an array of errors - empty if none found
     */
    public function validateProperty(string $name, $value)
    {
        $rules = $this->validation_rules[$name];
        switch($rules['type']){ //NB currently this is arbitrary - I can use any old key name in the model's validation rules
            case 'string':
                return $this->validateString(filter_var($value, FILTER_SANITIZE_STRING));
            case('email'):
                return $this->validateEmail(filter_var($value, FILTER_SANITIZE_EMAIL));
            case('password'):
                return $this->validatePassword(filter_var($value, FILTER_SANITIZE_STRING));
            default:
                return array();
        }
    }


    public function validatePropertyDynamic(string $name, $value, $args)
    {
        $validationMethodName = "validate" . ucwords($name);
        echo "<pre>"; var_dump($args); echo "</pre>";
        if(method_exists($this, $validationMethodName)){
            return $this->$validationMethodName($value, ...$args);
        }
    }

    /**
     * validate a string
     * [less than max length]
     *
     * @param  string $string
     * @param  [type] $max_length [description]
     * @return array             an array of errors - empty if none found
     */
    public function validateString(string $string, int $min_length = null, int $max_length = null)
    {
        $errors = []; echo "<p>$string $min_length $max_length</p>";
        if(empty($string)){
            $errors[] = "No value";
        }
        if(!is_string($string)){
            $errors[] = "Not a string";
        }
        if(isset($min_length) && strlen($string) < $min_length){
            $errors[] = "Must be at least " . $min_length . " characters";
        }
        if(isset($max_length) && strlen($string) > $max_length){
            $errors[] = "Must be no more than " . $max_length . " characters";
        }
        return $errors;
    }

    /**
     * validate a password
     * [ >= min length]
     * [including/excluding certain chars]
     * so this is in the base model class, but different applications may want different
     * password lengths, so in that case I would extend this method, right?
     *
     * @param  string $string
     * @param  integer $min_length minimum length, default 6
     * @return array             an array of errors - empty if none found
     */
    public function validatePassword(string $string, int $min_length = 8)
    {
        $errors = [];
        if(empty($string)){
            $errors[] = "No value";
        }
        if(!is_string($string)){
            $errors[] = "Not a string";
        }
        if(isset($min_length) && strlen($string) < $min_length){
            $errors[] = "Must be at least " . $min_length . " characters";
        }
        return $errors;
    }

    /**
     * validate email
     *
     * @param  string $email
     * @return array        an array of errors - empty if none found
     */
    public function validateEmail(string $email)
    {
        $errors = [];
        if(empty($email)){
            $errors[] = "No value";
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Not a valid email";
        }
        return $errors;
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
        // Traversy puts basic database query operations in his database class
        // i.e. make query, prepare statement, bind values, execute, which is a good approach I think
        // Also he has the methods I have above for getting all, getting where, count rows, etc in that
        // database class, not in a base model class like I'm doing here
        // Will be interesting to see if he has a base model later on and if so what he has in it
    }

}
