<?php

include_once '../config/db.php';
include_once '../objects/user.php';
include_once '../config/header.php';

$db = new Database();
$conn = $db->getConnection();

$newUser = new User($conn);
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->name) &&
    !empty($data->last) &&
    !empty($data->username) &&
    !empty($data->email) &&
    !empty($data->password)
){
    $newUser->name = $data->name;
    $newUser->last = $data->last;
    $newUser->username = $data->username;
    $newUser->email = $data->email;
    $newUser->password = $data->password;
  
    if($newUser->register()){
        http_response_code(201);
        echo json_encode(array("message" => "User was registered."));
    } else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to registed user."));
    }
} else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to register user. Data is incomplete."));
}
?>