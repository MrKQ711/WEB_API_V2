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
    $user_obj = new users($connection);

    if($_SERVER['REQUEST_METHOD'] === "GET") {

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

            $user_obj->id = $decode_data->data->id;

            

            // if(!empty($data->id)){
                //$student->id = $student_id;
                $data = $user_obj->delete_user();
                if(!empty($data)){
                    // set response code
                    http_response_code(200); // 200 means ok
                    // display message
                    echo json_encode(array("status" => 1, "message" => "Data deleted successfully"));
                } else {
                    // set response code
                    http_response_code(500); // 500 means internal server error
                    // display message
                    echo json_encode(array("status" => 0, "message" => "Failed to delete data"));
                }
            // } else {
            //     // set response code
            //     http_response_code(404); // 404 means not found
            //     // display message
            //     echo json_encode(array("status" => 0, "message" => "No data found"));
            // }

        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                "message" => "Access denied."
            ));
            return;
        }  
            $student_id = isset($_GET['id']) ? intval($_GET['id']) : "";
    
            
    
        } else {
            // set response code
            http_response_code(503); // 503 means service unavailable
            // display message
            echo json_encode(array("status" => 0, "message" => "Access denied"));  
    }

?>