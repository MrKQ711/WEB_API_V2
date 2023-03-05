<?php

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

        // get the data from the form
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->email) && !empty($data->password)){

            // set the data to the variables
            $user_obj->email = $data->email;
            //$user_obj->password = $data->password;  

            $user_data = $user_obj->check_login();

            if (!empty($user_data)){
                
                $name = $user_data['name'];
                $email = $user_data['email'];
                $password = $user_data['password'];

                if(password_verify($data->password, $password)){


                    $iss = "localhost";
                    $iat = time();
                    $nbf = $iat + 10;
                    $exp = $iat + 180;
                    $aud = "myusers";
                    $user_arr_data = array(
                        "id" => $user_data['id'],
                        "name" => $user_data['name'],
                        "email" => $user_data['email']
                    );

                    $secret_key = "owt125";

                    $payload_info = array(
                        "iss" => $iss,
                        "iat" => $iat,
                        "nbf" => $nbf,
                        "exp" => $exp,
                        "aud" => $aud,
                        "data" => $user_arr_data
                        // iss: Issuer of the token
                        // iat: Issued at
                        // nbf: Not before
                        // exp: Expire
                        // aud: Audience
                        // data: Data related to the signer user
                    );

                    //$jwt = JWT::encode($payload_info, $secret_key);
                    $jwt = JWT::encode($payload_info, $secret_key, 'HS512');


                    // set response code
                    http_response_code(200); // 200 means ok
                    // display message
                    echo json_encode(array("status" => 1, "jwt" => $jwt, "message" => "Login successful", "name" => $name, "email" => $email));
                } else {
                    // set response code
                    http_response_code(500); // 500 means internal server error
                    // display message
                    echo json_encode(array("status" => 0, "message" => "Invalid email or password"));
                }
            } else {
                http_response_code(404);
                echo json_encode(array("status" => 0, "message" => "Invalid email or password"));
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