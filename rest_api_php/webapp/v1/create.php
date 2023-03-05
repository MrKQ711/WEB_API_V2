<?php

    // include headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");


    // include database.php
    include_once ("../config/database.php");
    // include student.php
    include_once ("../classes/student.php");

    // create object of Database class
    $db = new Database();

    $connection = $db->connect();

    // create object of Student class
    $student = new Student($connection);

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        // get the data from the form
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->name) && !empty($data->email) && !empty($data->mobile)){

            // set the data to the variables
            $student->name = $data->name;
            $student->email = $data->email;
            $student->mobile = $data->mobile;

            if($student->create_data()){
                // set response code
                http_response_code(200); // 200 means ok
                // display message
                echo json_encode(array("status" => 1, "message" => "Data inserted successfully"));
            } else {
                // set response code
                http_response_code(500); // 500 means internal server error
                // display message
                echo json_encode(array("status" => 0, "message" => "Failed to insert data"));
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