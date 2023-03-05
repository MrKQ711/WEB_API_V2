<?php
use Firebase\JWT\Key;

    // include vendor
    require_once ("../vendor/autoload.php");
    use \Firebase\JWT\JWT;

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

        // body
        $data = json_decode(file_get_contents("php://input"));

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

        if(!empty($data->name) && !empty($data->description) && !empty($data->status)){
            
            try{


                $secret_key = "owt125";

                $decode_data = JWT::decode($jwt, new Key($secret_key, 'HS512'));

                $user_obj->user_id = $decode_data->data->id;
                $user_obj->project_name = $data->name;
                $user_obj->description = $data->description;
                $user_obj->status = $data->status;

                if($user_obj->create_project()){
                    http_response_code(200);
                    echo json_encode(array(
                        "status" => 1,
                        "message" => "Project created successfully."
                    ));
                } else {
                    http_response_code(500);
                    echo json_encode(array(
                        "status" => 0,
                        "message" => "Failed to create project."
                    ));
                }

            } catch(Exception $e) {
                http_response_code(500);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "Access denied.",
                    "error" => $e->getMessage()
                ));
            }

        } else {
            http_response_code(404);
            echo json_encode(array(
                "status" => 0,
                "message" => "All fields are required."
            ));
        }
    }
?>