<?php

class User {
    private $conn;
    private $table_name = "users";

    public $name;
    public $last;
    public $username;
    public $email;
    public $password;

    public function __construct($db){
        $this->conn = $db;
    }

    function signin(){
        $query = "SELECT name, last, username, email, password FROM " . $this->table_name . " WHERE username=:username AND password=:password LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        $this->username=htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(":username", $this->username);
        $this->password=htmlspecialchars(strip_tags($this->password));
        $stmt->bindParam(":password", $this->password);
        
        $stmt->execute();
        return $stmt;
    }

    function register(){
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, last=:last, username=:username, email=:email, password=:password";
        $stmt = $this->conn->prepare($query);

        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->last=htmlspecialchars(strip_tags($this->last));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":last", $this->last);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);

        if($stmt->execute()){
            return true;
        }
        return false;          
    }
}

?>