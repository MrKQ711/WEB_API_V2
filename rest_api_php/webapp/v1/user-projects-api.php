<?php

use Firebase\JWT\Key;

    // include vendor
    require_once ("../vendor/autoload.php");
    use \Firebase\JWT\JWT;

    ini_set('display_errors', 1); 
    // include headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");

    // include file
    include_once ("../config/database.php");
    include_once ("../classes/Users.php");

    // create object of Database class
    $db = new Database();
    $connection = $db->connect();

    $user_obj = new Users($connection);

    if($_SERVER['REQUEST_METHOD'] === "GET"){

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


        try{

            $secret_key = "owt125";

            $decode_data = JWT::decode($jwt, new Key($secret_key, 'HS512'));

            $user_obj->user_id = $decode_data->data->id;

            $projects = $user_obj->get_user_all_projects();

            if($projects->num_rows > 0){
                $projects_arr = array();
                $projects_arr['data'] = array();

                while($row = $projects->fetch_assoc()){
                    extract($row);

                    $project_item = array(
                        "id" => $id,
                        "name" => $name,
                        "description" => $description,
                        "user_id" => $user_id,
                        "status" => $status,
                        "created_at" => $created_at
                    );

                    array_push($projects_arr['data'], $project_item);
                }

                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "message" => "Projects found.",
                    "data" => $projects_arr
                ));
            } else {
                http_response_code(404);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "No projects found."
                ));
            }
        } catch(Exception $e){
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }     
    }
?>