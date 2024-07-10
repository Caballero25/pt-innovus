<?php
include "cors.php";
require_once '../vendor/autoload.php';
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/User.php';
require_once '../src/Auth.php';
require_once '../src/Middleware.php';

$method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($method=="POST") {
    makelogin();
} 


function makelogin() {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->username) && !empty($data->password)) {
        $database = new DB();
        $db = $database->getConnection();
        $user = new User($db);

        $user->username = $data->username;
        $user->password = $data->password;

        if ($user->login()) {
            $auth = new Auth();
            $token = $auth->generateToken($user->id);

            http_response_code(200);
            echo json_encode(["message" => "Login successful.", "jwt" => $token]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Login failed."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Incomplete data."]);
    }
}
?>