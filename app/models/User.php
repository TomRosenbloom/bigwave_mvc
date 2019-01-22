<?php

class User extends BaseModel
{

    public function __construct()
    {
        parent::__construct('users');
    }

    /**
     * confirm user is registered
     *
     * @param  string $email use this email to look up user
     * @return mixed boolean or -1 for error
     */
    public function confirmUserByEmail($email)
    {
        $results = $this->getAllWhere(array(array('name'=>'email', 'value'=>$email, 'comparison'=>' = ')));
        if(count($results) === 1){
            return true;
        }
        if(count($results) === 0){
            return false;
        }
        if(count($results > 1)){
            return -1;
        }
    }

    public function register($data)
    {
        // $this->connection->query('INSERT INTO users (name, email, password) VALUES(:name, :email, :password)');
        // $this->connection->bind(':name', $data['name']);
        // $this->connection->bind(':email', $data['email']);
        // $this->connection->bind(':password', $data['password']);

        $sql = "INSERT INTO users (name, email, password) VALUES (?,?,?)";
        $stmt = $this->connection->prepare($sql);

        if($stmt->execute([$data['name'], $data['email'], $data['password']])){
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * logs in user and returns user details - as array
     * 
     * @param type $email
     * @param type $password
     * @return boolean or array
     */
    public function login($email, $password)
    {
        $results = $this->getAllWhere(array(array('name'=>'email', 'value'=>$email, 'comparison'=>' = ')));
        if(count($results) === 1){
            $row = $results[0];
            $hashed_pwd = $row['password'];
            if(password_verify($password, $hashed_pwd)){
                return $row;
            } else {
                return false;
            }          
        }
        if(count($results) === 0){
            return false;
        }
        if(count($results > 1)){
            return -1;
        }       
    }
}
