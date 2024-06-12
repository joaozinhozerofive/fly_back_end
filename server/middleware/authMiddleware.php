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

        return true;
      }
      catch(Exception $e) {
        responseJson(AppError("Token inválido.", 401));
        return false;
      }

    }
    else {
      responseJson(AppError('Token inválido.', 401));
        return false;
    }
}
