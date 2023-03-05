<?php

    class Student{

        // declare variables
        public $name;
        public $email;
        public $mobile;

        public $id;

        private $conn;
        private $table_name;

        // constructor
        public function __construct($db){
            $this->conn = $db;
            $this->table_name = "tbl_students";
        }

        public function create_data(){

            // sql query to insert data
            $query = "INSERT INTO " . $this->table_name . " SET name = ?, email = ?, mobile = ?";

            // prepare the sql
            $obj = $this->conn->prepare($query);

            // sanitize input variables => basically it is used to remove any special characters from the input
            // like some specials characters like <, >, &, etc.
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->mobile = htmlspecialchars(strip_tags($this->mobile));

            // bind the parameters with prepare statement
            $obj->bind_param("sss", $this->name, $this->email, $this->mobile);

            if($obj->execute()){ // executing the query
                return true;
            } else {
                return false;
            }
        }

        // read all data
        public function get_all_data(){

            $sql_query = "SELECT * FROM " . $this->table_name; 

            $std_obj = $this->conn->prepare($sql_query);

            // execute the query
            $std_obj->execute();

            return $std_obj->get_result();
        }

        // read single data
        public function get_single_student(){

            $sql_query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";

            $obj = $this->conn->prepare($sql_query);

            $obj->bind_param("i", $this->id);

            $obj->execute();

            $data = $obj->get_result();
            return $data->fetch_assoc();
        }

        // update data
        public function update_student(){

            // query to update data
            $sql_query = "UPDATE " . $this->table_name . " SET name = ?, email = ?, mobile = ? WHERE id = ?";

            // prepare the query
            $query_object = $this->conn->prepare($sql_query);

            // sanitize the input
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->mobile = htmlspecialchars(strip_tags($this->mobile));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // bind the parameters
            $query_object->bind_param("sssi", $this->name, $this->email, $this->mobile, $this->id);

            if ($query_object->execute()) {
                return true;
            } else {
                return false;
            }
        }

        // delete data
        public function delete_student(){
                
                // query to delete data
                $sql_query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    
                // prepare the query
                $query_object = $this->conn->prepare($sql_query);
    
                // sanitize the input
                $this->id = htmlspecialchars(strip_tags($this->id));
    
                // bind the parameters
                $query_object->bind_param("i", $this->id);
    
                if ($query_object->execute()) {
                    return true;
                } else {
                    return false;
                }
        }
    }

?>