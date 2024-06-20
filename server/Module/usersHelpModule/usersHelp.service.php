<?php 
class UsersHelpService {
    public static function create($data) {
        $user_name   = $data->user_name;
        $email       = $data->email;
        $description = $data->description;

        self::validateDataUsersHelpCreate($data);

        try {
            (new qbquery('users_help'))
            ->insert([
                'user_name'   => $user_name,
                'email'       => $email,
                'description' => $description,
            ]);

            AppSucess("Pedido de ajuda enviado com sucesso.", 201);
        }   
        catch(Exception $e) {
            AppError("Não foi possível inserir este pedido de ajuda.", 400);
        }
    }

    public static function validateDataUsersHelpCreate($data) {
        $user_name   = $data->user_name;
        $email       = $data->email;
        $description = $data->description;

        if(!checkEmail($email)) {
            AppError("Email inválido.", 400);
        }

        if(!trim($user_name)) {
            AppError('Nome do usuário é obrigatório', 401);
        }

        if(!trim($email)) {
            AppError('Email do usuário é obrigatório', 401);
        }
        if(!trim($description)) {
            AppError('Por favor, informe sua dúvida.', 400);
        }
    }
}