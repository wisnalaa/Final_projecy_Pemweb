<?php

class createDb {

    public $servername;
    public $username;
    public $password;
    public $dbname;
    public $tablename;
    public $conn;

    // Constructor
    public function __construct(
        $dbname = "dbmebel",
        $tablename = "Product",
        $servername = "localhost",
        $username = "root",
        $password = ""
    ) {
        $this->dbname = $dbname;
        $this->tablename = $tablename;
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;

        // Create connection
        $this->conn = new mysqli($servername, $username, $password);
 
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Query to create database
        $sql = "CREATE DATABASE IF NOT EXISTS $dbname";

        // Execute query to create database
        if(mysqli_query($this->conn, $sql)){

            $this->conn = mysqli_connect($servername, $username, $password, $dbname);

            // sql to create new table
            $sql = "CREATE TABLE IF NOT EXISTS $tablename (
                id_product int PRIMARY KEY AUTO_INCREMENT,
                name varchar(100),
                color varchar(100),
                price int,
                quantity int,
                image varchar(255)
            );";

            if(!mysqli_query($this->conn,$sql)){
                echo "Error creating table: ". mysqli_error($this->conn);
            }
        
        }else{
            return false;
        }

    }

    // get product from database
    public function getData(){
        $sql = "SELECT * FROM $this->tablename";

        $result = mysqli_query($this->conn, $sql);

        if(mysqli_num_rows($result) > 0){
            return $result;
        }
    }
    
}

?>