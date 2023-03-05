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

        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->jwt)){

            $header = getallheaders();

            //$data = json_decode(file_get_contents("php://input"));
            if(empty($header['Authorization'])){
                http_response_code(500);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "Access denied."
                ));
                return;
            } else {
                $data->jwt = $header['Authorization'];
            }
            

            if(!empty($data->jwt)){

                try {
                    
                $secret_key = "owt125";

                $decode_data = JWT::decode($data->jwt, new Key($secret_key, 'HS512'));

                http_response_code(200);

                echo json_encode(array(
                    "status" => 1,
                    "message" => "Access granted.",
                    "data" => $data,
                    "user_data" => $decode_data
                ));
                } catch(Exception $e) {
                    http_response_code(500);
                    echo json_encode(array(
                        "status" => 0,
                        "message" => "Access denied.",
                        "error" => $e->getMessage()
                    ));
                }
            }

        }
    }
?>