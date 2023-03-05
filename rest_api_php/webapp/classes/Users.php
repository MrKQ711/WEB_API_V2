<?php


    class Users{
        
        // declare variables
        public $name;
        public $email;
        public $password;

        public $id;

        public $user_id;
        public $project_name;
        public $description;
        public $status;

        private $conn;
        private $users_tbl;
        private $projects_tbl;



        public function __construct($db){
            $this->conn = $db;
            $this->users_tbl = "tbl_users";
            $this->projects_tbl = "tbl_projects";
        }

        public function create_user(){
            $user_query = "INSERT INTO " . $this->users_tbl . " SET name = ?, email = ?, password = ?";
            
            $user_obj = $this->conn->prepare($user_query);

            $user_obj->bind_param("sss", $this->name, $this->email, $this->password);

            if($user_obj->execute()){
                return true;
            } else {
                return false;
            }
        }

        // check if email already exists
        public function check_email(){

            $email_query = "SELECT * FROM " . $this->users_tbl . " WHERE email = ?";

            $user_obj = $this->conn->prepare($email_query);

            $user_obj->bind_param("s", $this->email);

            if($user_obj->execute()){
                $data = $user_obj->get_result();
                return $data->fetch_assoc();
            } else {
                return array();
            }
        }

        public function check_login(){
                
                $login_query = "SELECT * FROM " . $this->users_tbl . " WHERE email = ?";
    
                $user_obj = $this->conn->prepare($login_query);
    
                $user_obj->bind_param("s", $this->email);
    
                if($user_obj->execute()){
                    $data = $user_obj->get_result();
                    return $data->fetch_assoc();
                } else {
                    return array();
                }
        }

        // to create projects
        public function create_project(){
            $project_query = "INSERT INTO " . $this->projects_tbl . " SET user_id = ?, name = ?, description = ?, status = ?";
            
            $project_obj = $this->conn->prepare($project_query);


            // sanitize input variables => basically it is used to remove any special characters from the input
            $project_name = htmlspecialchars(strip_tags($this->project_name));
            $description = htmlspecialchars(strip_tags($this->description));
            $status = htmlspecialchars(strip_tags($this->status));

            // bind the parameters with prepare statement
            $project_obj->bind_param("isss", $this->user_id, $project_name, $description, $status);
            if($project_obj->execute()){
                return true;
            } else {
                return false;
            }
        }

        // to get all projects
        public function get_all_projects(){

            

            $project_query = "SELECT * FROM " . $this->projects_tbl . " ORDER BY id DESC";
            
            $project_obj = $this->conn->prepare($project_query);

            $project_obj->execute();

            return $project_obj->get_result(); 
        }

        // to get all projects of a user
        public function get_user_all_projects(){
            $project_query = "SELECT * FROM " . $this->projects_tbl . " WHERE user_id = ? ORDER BY id DESC";
            
            $project_obj = $this->conn->prepare($project_query);

            $project_obj->bind_param("i", $this->user_id);

            $project_obj->execute();

            return $project_obj->get_result();
        }

        // CRUD operations

        public function get_all_data(){

            $sql_query = "SELECT * FROM " . $this->users_tbl; 

            $std_obj = $this->conn->prepare($sql_query);

            // execute the query
            $std_obj->execute();

            return $std_obj->get_result();
        }


        public function get_single_user(){

            $sql_query = "SELECT * FROM " . $this->users_tbl . " WHERE id = ?";

            $obj = $this->conn->prepare($sql_query);

            $obj->bind_param("i", $this->id);

            $obj->execute();

            $data = $obj->get_result();
            return $data->fetch_assoc();
        }

        // update data
        public function update_user(){

            // query to update data
            $sql_query = "UPDATE " . $this->users_tbl . " SET name = ?, email = ?, password = ? WHERE id = ?";

            // prepare the query
            $query_object = $this->conn->prepare($sql_query);

            // sanitize the input
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = htmlspecialchars(strip_tags($this->password));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // bind the parameters
            $query_object->bind_param("sssi", $this->name, $this->email, $this->password, $this->id);

            if ($query_object->execute()) {
                return true;
            } else {
                return false;
            }
        }

        // delete data
        public function delete_user(){
                
                // query to delete data
                $sql_query = "DELETE FROM " . $this->users_tbl . " WHERE id = ?";
    
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
