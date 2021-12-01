<?php

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/db.php';
include_once '../objects/user.php';

$db = new Database();
$conn = $db->getConnection();

$newUser = new User($conn);
$data = json_decode(file_get_contents("php://input"));

$input_name = $data->name;
$input_last = $data->last;
$input_username = $data->username;
$input_email = $data->email;
$input_pass = $data->password;

if(
    !empty($input_name) &&
    !empty($input_last) &&
    !empty($input_username) &&
    !empty($input_email) &&
    !empty($input_pass)
){
    $newUser->name = $input_name;
    $newUser->last = $input_last;
    $newUser->username = $input_username;
    $newUser->email = $input_email;
    $newUser->password = $input_pass;
  
    if($newUser->register()){
        http_response_code(201);
        echo json_encode(
            array(
                "message" => "User was registered."
            ));
    } else{
        http_response_code(503);
        echo json_encode(
            array(
                "message" => "Unable to registed user."
            ));
    }
} else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to register user. Data is incomplete."));
}
?>