<?php
include_once 'config.php';
include_once '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

const KEY = "mipruebatecnica";

class Auth {
    public function generateToken($user_id) {
        $payload = [
            'iss' => "http://localhost", // Emisor del token
            'aud' => "http://localhost", // Audiencia del token
            'iat' => time(), // Tiempo en que se generó el token
            'nbf' => time(), // Tiempo antes del cual el token no es válido
            'exp' => time() + (600 * 600), // Tiempo en que expira el token (segundos)
            'data' => [
                'user_id' => $user_id
            ]
        ];

        return JWT::encode($payload, KEY, 'HS256');
    }

    public function verifyToken($jwt) {
        try {
            $decoded = JWT::decode($jwt, new Key(KEY, 'HS256'));
            return $decoded; 
        } catch (Exception $e) {
            return null;
        }
    }
}
?>
