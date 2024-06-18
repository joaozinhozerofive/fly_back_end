<?php
use Firebase\JWT\Key;
require_once(dirname(__DIR__) . '/vendor/autoload.php');
use Firebase\JWT\JWT;

function isAuthenticated() {
    $bearerToken = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
    $token       = str_replace('Bearer','', end($bearerToken));
    $authSecret  = getenv('AUTH_SECRET');

    if($token) {
      try{
        $payload = JWT::decode($token, new Key($authSecret, 'HS256'));
        $_REQUEST['user_id'] = $payload->user_id;
        unset($payload->exp);
      }
      catch(Exception $e) {
        AppError("Token inválido.", 401);
        die;
      }

    }
    else {
      AppError('Token inválido.', 401);
      die;
    }
}
