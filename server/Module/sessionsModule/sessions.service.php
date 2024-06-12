<?php 
require_once($parentDir . '/vendor/autoload.php');
use Firebase\JWT\JWT;

class SessionsService {
    public static function create($data) {
     $email = $data->email;
     $password = $data->password;

     self::validateDataCreate($data);

     $user = self::getUserByEmailAndaPassword($email, $password);
     unset($user->password); // Por segurança, remove a senha do usuário  do objeto.

     if(!$user) {  
        return AppError("E-mail ou senha inválidos.", 401);
     }

     $token = self::newToken($user);

     return [
        'user'  => $user, 
        'token' => $token
     ];
    }

    private static function validateDataCreate($data) {
      $email = $data->email;
      $password = $data->password;
 
      if(!checkEmail($email)) {
         AppError("E-mail inválido", 400);
      }
     
      if(!trim($email)) {
         AppError("E-mail é obrigatório para iniciar sessão.", 400);
      }
 
      if(!trim($password)) {
         AppError("Senha é obrigatória para iniciar sessão.", 400);
      }
    }


    private static function getUserByEmailAndaPassword($email, $password) { 
        $user =  getData('users', ['email' => $email]);   
        
        if(!$user){
            return false;
        }

        $passwordMatch = password_verify($password, $user->password);

        if($passwordMatch) {
            return $user;
        }
    }

    private static function newToken($user) {
    $optionsToken = [
        'user_id' =>  $user->id, 
        'email' => $user->email, 
        'exp' => time() + (60 * 60 * 24 * 2) // 2 days.
     ];

     $token = JWT::encode($optionsToken, getenv('AUTH_SECRET'), 'HS256');
    
     return $token;
    }
}