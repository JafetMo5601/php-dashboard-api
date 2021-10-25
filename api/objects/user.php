<?php

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $last;
    public $username;
    public $email;
    public $password;

    public function __construct($db){
        $this->conn = $db;
    }

    function signin(){
        $query = "SELECT username, `password` FROM " . $this->table_name . " WHERE username=:username";
        $stmt = $this->conn->prepare($query);
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