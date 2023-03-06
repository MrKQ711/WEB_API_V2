<?php

use Firebase\JWT\Key;

    // include vendor
    require_once ("../vendor/autoload.php");
    use \Firebase\JWT\JWT;

    // include headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");


    // include database.php
    include_once ("../config/database.php");
    // include student.php
    include_once ("../classes/Users.php");

    // create object of Database class
    $db = new Database();

    $connection = $db->connect();

    // create object of Student class
    $user_obj = new Users($connection);

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $header = getallheaders();

        if(empty($header['Authorization'])){
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                "message" => "Access denied."
            ));
            return;
        } else {
            $jwt = $header['Authorization'];
        }


        try {

            $secret_key = "owt125";

            $decode_data = JWT::decode($jwt, new Key($secret_key, 'HS512'));

            // $user_obj->name = $decode_data->data->name;
            // $user_obj->email = $decode_data->data->email;

            if ($decode_data->data->name == "Thai_V3" && $decode_data->data->email == "thainhse161457@gmail.com") {
                // get the data from the form
                $data = json_decode(file_get_contents("php://input"));

                if(!empty($data->id) && !empty($data->name) && !empty($data->email) && !empty($data->password)){

            // set the data to the variables
                    $user_obj->id = $data->id;
                    $user_obj->name = $data->name;
                    $user_obj->email = $data->email;
                    $user_obj->password = password_hash($data->password, PASSWORD_DEFAULT);

                    if($user_obj->update_user()){
                // set response code
                        http_response_code(200); // 200 means ok
                // display message
                    echo json_encode(array("status" => 1, "message" => "Data updated successfully"));
                    } else {
                // set response code
                        http_response_code(500); // 500 means internal server error
                // display message
                        echo json_encode(array("status" => 0, "message" => "Failed to update data"));
                    }

                } else {
            // set response code
                    http_response_code(404); // 404 means not found
            // display message
                    echo json_encode(array("status" => 0, "message" => "All fields are required"));
                }
            } else {
                http_response_code(500);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "You don't have permission to access access this."
                ));
                return;
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                "message" => "Access denied."
            ));
            return;
        }

        

    } else {
        // set response code
        http_response_code(503); // 503 means service unavailable
        // display message
        echo json_encode(array("status" => 0, "message" => "Access denied"));
    }

?>