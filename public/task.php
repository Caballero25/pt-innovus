<?php
include "cors.php";
require_once '../vendor/autoload.php';
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/User.php';
require_once '../src/Auth.php';
require_once '../src/Middleware.php';
require_once '../src/Task.php';

$method = strtoupper($_SERVER['REQUEST_METHOD']);
$middleware = new Middleware;
// Endpoints de Tareas
if ($method == 'POST') {
    $user_id = $middleware->protect(); // Verificar token
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->title) && isset($data->description)) {
        $task = new Task();
        if ($task->create($user_id, $data->title, $data->description)) {
            http_response_code(201);
            echo json_encode(["message" => "Task created successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to create task"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Invalid input"]);
    }
}

elseif ($method == 'GET') {
    $user_id = $middleware->protect(); // Verificar token
    $task = new Task();
    $tasks = $task->read($user_id);

    if ($tasks) {
        http_response_code(200);
        echo json_encode($tasks);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "No tasks found"]);
    }
}

elseif ($method == 'PUT') {
    $user_id = $middleware->protect(); // Verificar token
    $data = json_decode(file_get_contents("php://input"));
    $id = basename($_SERVER['REQUEST_URI']);

    if (isset($data->title) && isset($data->description)) {
        $task = new Task();
        if ($task->update($id, $user_id, $data->title, $data->description)) {
            http_response_code(200);
            echo json_encode(["message" => "Task updated successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to update task"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Invalid input"]);
    }
}

elseif (($method == 'DELETE')) {
    $user_id = $middleware->protect(); // Verificar token
    $id = basename($_SERVER['REQUEST_URI']);

    $task = new Task();
    if ($task->delete($id, $user_id)) {
        http_response_code(200);
        echo json_encode(["message" => "Task deleted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to delete task"]);
    }
}
?>



?>


?>