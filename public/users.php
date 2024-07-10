<?php
include "cors.php";
require_once '../vendor/autoload.php';
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/User.php';
require_once '../src/Auth.php';
require_once '../src/Middleware.php';

$method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($method == 'POST') {
    register();
} 
elseif ($method == 'GET') {
    $user_id = $middleware->protect(); // Verificar token
    getProfile($user_id);
};

function register() {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->username) && !empty($data->password)) {
        $database = new DB();
        $db = $database->getConnection();
        $user = new User($db);

        $user->username = $data->username;
        $user->password = password_hash($data->password, PASSWORD_BCRYPT);

        if ($user->create()) {
            http_response_code(201);
            echo json_encode(["message" => "User was created."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "Unable to create user."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Incomplete data."]);
    }
}

function getProfile($user_id) {
    echo json_encode(["message" => "Access granted.", "user_id" => $user_id]);
}




