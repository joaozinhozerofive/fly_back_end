<?php 
require_once($parentDir . '\services\utils\index.php');

class UsersController {

    public function create() {
     $body = request();

     if(!pr_value($body, 'nome')){
        return AppError('É necessário informar o nme para continuar.');
     }

     if(!pr_value($body, 'email')){
        return AppError('É necessário informar um e-mail para continuar.');
     }    
     

     try{

    }catch(Exception $e) {
        return AppError('Não foi possível criar usuário, tente novamente.', 404);
    }finally{
        return AppSucess('Usuário criado com sucesso');
    }
    }


    public function update() {
    }

    public function show() {
    }

    public function delete() {
    }
}

?>