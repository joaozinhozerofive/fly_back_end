<?php

class UserService {
    public static function create($data) {
        $username     = $data->username;
        $email        = $data->email;
        $privilege    = $data->privilege;
        $gender       = $data->gender;
        $phone        = fmt_phone($data->phone);  
        $cpf          = fmt_cpf($data->cpf);
        $birth        = fmt_data($data->birth);  
        $password     = $data->password; 
        $passwordHash = password_hash($password, PASSWORD_DEFAULT); 

        self::validadeDataCreate($data);

        try {
          (new qbquery('users'))
          ->insert([
            'username'  => $username, 
            'email'     => $email, 
            'privilege' => $privilege,
            'gender'    => $gender,
            'phone'     => $phone, 
            'birth'     => $birth,
            'cpf'       => $cpf,
            'password'  => $passwordHash, // Usar password_verify para controlar a sessão do usuário.
          ]);  

           AppSucess("Usuário criado com sucesso!", 201);
        }
        catch(Exception $e) {
           AppError("Não foi possível criar usuário.", 400);
        }
    }

    public static function show($params =  null) {
        $user = self::getUserByRouteParams($params);

        return $user;
    }

    public static function update($id, $data) {
        if(!$id) {
            AppError('Informe o id do usuário para continuar.', 400);
        }

        $user = getData('users', ['id' => $id]);

        if(!$user) {
            AppError('Usuário não encontrado.', 404);
        }
        
        $user = self::getDataUserUptade($user, $data);
        
        if(!trim($user->cpf)) {
            AppError("CPF é obrigatório, tente novamente inserindo um.", 400);
        }

        if(!checkEmail($user->email)) {
            AppError('E-mail inválido', 400); 
         }

        if(strlen($user->cpf) != 11) {
            AppError("CPF inválido.", 400);
        }
        
        if(self::cpfExists($user)) {
            AppError("Já existe um usuário cadastrado com este CPF.", 400);
        }

        if(self::emailExists($user)) {
            AppError("Já existe um usuário cadastrado com este e-mail.", 400);
        }


        try {
            (new qbquery('users'))
            ->update($user, ['id' => $id]);

            AppSucess('Usuário atualizado com sucesso!');
        }
        catch (Exception $e) {
            AppError("Não foi possível atualizar usuário.", 400);
        }
    }

    private static function validadeDataCreate($data) {
        $username     = $data->username;
        $email        = $data->email;
        $privilege    = $data->privilege;
        $gender       = $data->gender;
        $phone        = fmt_phone($data->phone);  
        $cpf          = fmt_cpf($data->cpf);
        $birth        = fmt_data($data->birth);  
        $password     = $data->password; 
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        if(!trim($username)) {
            AppError("O campo 'nome' é obrigatório.", 400);
        }

        if(!trim($email)) {
            AppError("O campo 'email' é obrigatório.", 400);
        }

        if(!trim($password)) {
            AppError("O campo 'senha' é obrigatório.", 400);
        }

        if(!checkEmail($email)) {
            AppError('E-mail inválido', 400); 
        }

        if(trim($cpf) && strlen($cpf) != 11) {
            AppError("CPF inválido.", 400);
        }

        if(trim($cpf) && getData('users', ['cpf' => $cpf])) {
            AppError("Já existe um usuário com este CPF cadastrado.", 409);
        };

        if(getData('users', ['email' => $email])) {
            AppError("Já existe um usuário com este e-mail cadastrado.", 409);
        }
    }

    private static function getDataUserUptade($user, $data) {
        $username     = $data->username;
        $email        = $data->email;
        $privilege    = $data->privilege;
        $gender       = $data->gender;
        $phone        = fmt_phone($data->phone);  
        $cpf          = fmt_cpf($data->cpf);
        $birth        = fmt_data($data->birth);  
        $is_active    = $data->is_active;
        $password     = $data->password; 
        $passwordHash = password_hash($password, PASSWORD_DEFAULT); 

        $user->username  = trim($username)  ? $username       : $user->username; 
        $user->email     = trim($email)     ? $email          : $user->email;
        $user->privilege = trim($privilege) ? $privilege      : $user->privilege;
        $user->gender    = trim($gender)    ? $gender         : $user->gender;
        $user->phone     = trim($phone)     ? $phone          : $user->phone;
        $user->cpf       = trim($cpf)       ? $cpf            : $user->cpf;
        $user->birth     = trim($birth)     ? $birth          : $user->birth;
        $user->password  = trim($password)  ? $passwordHash   : $user->password;
        $user->is_active = trim($is_active) == 1 || trim($is_active) == 0 ? $is_active : $user->is_active;
        
        return $user;
    }

    private static function cpfExists($user) {
        $cpfExists = getData('users', [
            'cpf' => $user->cpf
        ]);
        if($cpfExists) {
           return $cpfExists->id != $user->id;
        }

        return false;
    }

    private static function emailExists($user) {
        $emailExists = getData('users', [
            'email' => $user->email
        ]);

        if($emailExists) {
           return $emailExists->id != $user->id;
        }

        return false;
    }

    private static function getUserByRouteParams($params = null) {
        if(!empty($params['id'])) {
            $user = self::getUserById($params['id']);

            if(!$user) {
                AppError('Usuário não encontrado.', 404);
            }
        }

        if($params){ 
            return self::getUsersByLikeParams($params);
        }

        $user = (new qbquery('users'))
        ->orderBy(['id DESC'])
        ->getMany();

        return $user;
    }

    private static function getUserById($id) {
          return (new qbquery('users'))
            ->caseWhen('gender', [
                '0' => 'Não informado', 
                '1' => 'Masculino',
                '2' => 'Feminino'
            ], 'userGender')
            ->where(['id' => $id])
            ->getFirst();
    }

    private static function getUsersByLikeParams($params) {
        $params['id'] = intval($params['id']);
        $users = (new qbquery('users'))
        ->caseWhen('gender', [
             '0' => 'Não informado', 
            '1' => 'Masculino',
            '2' => 'Feminino'
        ], 'userGender')
        ->whereLike($params)
        ->getMany();
    }
}

?>