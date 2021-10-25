<?php 

include_once '../config/db.php';
include_once '../objects/user.php';
include_once '../config/header.php';

use \Firebase\JWT\JWK;

$db = new Database();
$conn = $db->getConnection();

// $user = new User($conn);
$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->username) &&
    !empty($data->password)
){} else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to signin user. Data is incorrect."));
}

$stmt = $user->read();
$num = $stmt->rowCount();
  
if($num>0){
    $users_arr=array();
    $users_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $user_item=array(
            "id" => $id,
            "name" => $name,
            "last" => $last,
            "username" => $username,
            "email" => $email,
            "password" => $password
        );
  
        array_push($users_arr["records"], $user_item);
    }
    http_response_code(200);
    echo json_encode($users_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No users found."));
}

?>