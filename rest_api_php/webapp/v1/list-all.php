<?php

use Firebase\JWT\Key;

    // include vendor
    require_once ("../vendor/autoload.php");
    use \Firebase\JWT\JWT;

    ini_set('display_errors', 1);
    // include headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");


    // include database.php
    include_once ("../config/database.php");
    // include student.php
    include_once ("../classes/Users.php");

    // create object of Database class
    $db = new Database();

    $connection = $db->connect();

    // create object of Student class
    $user_obj = new Users($connection);

    if ($_SERVER['REQUEST_METHOD'] === "GET") {

        $header = getallheaders();

        if (empty($header['Authorization'])) {
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

            $user_obj->name = $decode_data->data->name;
            $user_obj->email = $decode_data->data->email;

            if($user_obj->name == "Thai_V3" && $user_obj->email == "thainhse161457@gmail.com"){
                $user_obj->id = $decode_data->data->id;

                $data = $user_obj->get_all_data();

                if ($data->num_rows > 0) {
                    $student_array = array();
                    $student_array['data'] = array();
        
                    while ($row = $data->fetch_assoc()) {
                        $student_item = array(
                            "id" => $row['id'],
                            "name" => $row['name'],
                            "email" => $row['email'],
                            "password" => $row['password'],
                            "created_at" => date("d-m-Y h:i:s A", strtotime($row['created_at']))
                        );
                        array_push($student_array['data'], $student_item);
                    }
        
                    // set response code
                    http_response_code(200); // 200 means ok
                    // display message
                    echo json_encode(array("status" => 1, "message" => "Data found", "data" => $student_array));
                } else {
                    // set response code
                    http_response_code(404); // 404 means not found
                    // display message
                    echo json_encode(array("status" => 0, "message" => "No data found"));
                }

            }  else {
                // set response code
                http_response_code(503); // 503 means service unavailable
                // display message
                echo json_encode(array("status" => 0, "message" => "You don't have permission to access."));
            }
        }  catch (Exception $e) {
            // set response code
            http_response_code(500); // 500 means internal server error
            // display message
            echo json_encode(array("status" => 0, "message" => "Access denied", "error" => $e->getMessage()));
        }
    }
    
?>