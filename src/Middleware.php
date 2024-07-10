<?php
include_once 'Auth.php';

class Middleware {
    public function protect() {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["message" => "Authorization header not found"]);
            exit();
        }

        $authHeader = $headers['Authorization'];
        list($jwt) = sscanf($authHeader, 'Bearer %s');

        if ($jwt) {
            $auth = new Auth();
            $decoded = $auth->verifyToken($jwt);

            if ($decoded && isset($decoded->data->user_id)) {
                return $decoded->data->user_id;
            }
        }

        http_response_code(401);
        echo json_encode(["message" => "Access denied"]);
        exit();
    }
}
?>
