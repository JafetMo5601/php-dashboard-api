<?php 

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/db.php';
include_once '../objects/user.php';

require __DIR__ . '../../../vendor/autoload.php';

use \Firebase\JWT\JWT;

$db = new Database();
$conn = $db->getConnection();

$registeredUser = new User($conn);
$data = json_decode(file_get_contents("php://input"));

$input_username = $data->username;
$input_pass = $data->password;

if(
    !empty($input_username) &&
    !empty($input_pass)
){
    $registeredUser->username = $input_username;
    $registeredUser->password = $input_pass;

    $stmt = $registeredUser->signin();
    $numOfRows = $stmt->rowCount();

    if($numOfRows > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $name = $row['name'];
        $last = $row['last'];
        $username = $row['username'];
        $email = $row['email'];
        $password = $row['password'];
        $secret_key = "D4shb0@rd";
        $issuer_claim = "localhost"; 
        $audience_claim = "THE_AUDIENCE";
        $issuedat_claim = time();
        $notbefore_claim = $issuedat_claim + 10; 
        $expire_claim = $issuedat_claim + 60; 

        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "name" => $name,
                "last" => $last,
                "username" => $username,
                "email" => $email
        ));

        $jwtValue = JWT::encode($token, $secret_key);
        http_response_code(200);
        echo json_encode(
            array(
                "message" => "success",
                "token" => $jwtValue,
                "expiry" => $expire_claim
            ));
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Username or password incorrect."));
    }
} else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to signin user. Data is incomplete."));
}

?>