<?php

    ini_set('display_errors', 1);
    // include headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");

    // include file
    include_once ("../config/database.php");
    include_once ("../classes/Users.php");

    // create object of Database class
    $db = new Database();
    $connection = $db->connect();

    $user_obj = new Users($connection);

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        // get the data from the form
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->name) && !empty($data->email) && !empty($data->password)){

            // set the data to the variables
            $user_obj->name = $data->name;
            $user_obj->email = $data->email;
            $user_obj->password = password_hash($data->password, PASSWORD_DEFAULT); 

            $email_data = $user_obj->check_email(); 

            if(empty($email_data)){
                if($user_obj->create_user()){
                    // set response code
                    http_response_code(200); // 200 means ok
                    // display message
                    echo json_encode(array("status" => 1, "message" => "User created successfully"));
                } else {
                    // set response code
                    http_response_code(500); // 500 means internal server error
                    // display message
                    echo json_encode(array("status" => 0, "message" => "Failed to create user"));
                }
            } else {
                // set response code
                http_response_code(500); // 500 means internal server error
                // display message
                echo json_encode(array("status" => 0, "message" => "Email already exists"));
            }
        } else {
            // set response code
            http_response_code(404); // 404 means not found
            // display message
            echo json_encode(array("status" => 0, "message" => "All fields are required"));
        }

    } else {
        // set response code
        http_response_code(503); // 503 means service unavailable
        // display message
        echo json_encode(array("status" => 0, "message" => "Access denied"));
    }

?>