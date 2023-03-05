<?php

    class Database{

        // variable declaration
        private $hostname;
        private $dbname;
        private $username;
        private $password;
        private $conn;

        public function connect(){
            // variable initialization
            // $this->hostname = "abcd";
            // $this->dbname = "rest_php_api";
            // $this->username = "root";
            // $this->password = "";

            // connection
            // $this->conn = new mysqli("localhost", "root", "", "rest_php_api");
            $this->conn = new mysqli("api", "thai11", "passne", "rest_php_api");

            if($this->conn->connect_error){
                // true => it means that is has some error
                print_r($this->conn->connect_error);
                exit();
            } else {
                // false => it means no error in connection details
                return $this->conn;
                //echo "--Successfully connected--";
                // print_r($this->conn);
            }
        }
    }

    // $db = new Database();

    // $db->connect();
    // echo "Connected";
?>