<?php
require_once($parentDir . '/vendor/autoload.php');
require_once($parentDir . '/services/services.php');

class AdressService{ 
    public static function create($data) {
        $user_id        = getUserId();
        $street_adress  = $data->street_adress;
        $residence_code = $data->residence_code;
        $country        = $data->country;
        $city           = $data->street_adress;
        $district       = $data->district;
        $state          = $data->state;
        $cep            = fmt_cep($data->cep);
        $complement     = $data->complement;

        self::validateDataCreate($data);
        
        try{
            (new qbquery('adress'))->insert([
                'user_id'        => $user_id,
                'street_adress'  => $street_adress,
                'residence_code' => $residence_code,
                'country'        => $country,
                'city'           => $street_adress,
                'district'       => $district,
                'state'          => $state,
                'cep'            => $cep,
                'complement'     => $complement
            ]);
            
            AppSucess("Endereço criado com sucesso!", 201);
        }
        catch(Exception $e) {
            AppError("Não foi possível criar endereço.", 400);
        }
    }

    public static function show($params = null) {
        $params['id'] = intval($params['id']);

        if(!empty($params['id'])) {
            $adress = getData('adress', ['id' => $params['id']]);
            return $adress;
        }

        if($params) {
            return self::getAdressByLikeParams($params);
        }

        return self::getManyAdress();
    }

    public static function update($id, $data) {
        if(!$id) {
            AppError("Informe um endereço para continuar.", 400);
        }

        $adress = getData('adress', ['id' => $id]);

        if(!$adress) {
            AppError("Endereço não encontrado.", 404);
        }

        $adress = self::getDataAdressUpdate($adress, $data);

        if(strlen($adress->state) > 5) {
            AppError("O estado deve ter no máximo 5 caracteres.", 400);
        }

        if(strlen($adress->complement) > 60) {
            AppError("Complemento deve ter no máximo 60 caracteres.", 400);
        }
        
        if(!self::checkAdressExistsByCep($adress->cep)){ 
            AppError("CEP inválido.", 400);
        }

        try {
            (new qbquery('adress'))
            ->update($adress, ['id' => $id]);

            AppSucess("Endereço atualizado com sucesso.");
        }
        catch(Exception $e) {
            AppError("Não foi possível atualizar endereço.", 400);
        }

    }

    public static function delete($id){
        if($id) {
            $adress = getData('adress', ['id' => $id]);
        }

        if(!$id) {
            AppError("Informe um endereço para continuar.", 400);
        }

        if(!$adress) {
            AppError("Endereço não encontrado.", 404);
        }

        try{
            (new qbquery('adress'))
            ->delete("id = $id");

            AppSucess("Endereço excluído com sucesso.");
        }
        catch(Exception $e) {
            AppError("Não foi possível excluir endereço.", 400);
        }
    }

    public static function validateDataCreate($data) {
        $user_id        = getUserId();
        $street_adress  = $data->street_adress;
        $residence_code = $data->residence_code;
        $country        = $data->country;
        $city           = $data->street_adress;
        $district       = $data->district;
        $state          = $data->state;
        $cep            = fmt_cep($data->cep);
        $complement     = $data->complement;
        
        if(!trim($cep)) {
            AppError("CEP é obrigatório.", 400);
        }

        if(!trim($street_adress)) {
            AppError("Rua é obrigatório.", 400);
        }

        if(!trim($residence_code)) {
            AppError("Número do endereço é obrigatório.", 400);
        }

        if(!trim($country)) {
            AppError("País é obrigatório.", 400);
        }

        if(!trim($city)) {
            AppError("Cidade é obrigatório.", 400);
        }

        if(!trim($district)) {
            AppError("Bairro é obrigatório.", 400);
        }

        if(!trim($state)) {
            AppError("Estado é obrigatório.", 400);
        }

        if(!self::checkAdressExistsByCep($cep)) {
            AppError("CEP inválido.", 400);
        }
    }

    public static function checkAdressExistsByCep($cep) {
        $cep = fmt_cep($cep);
        if(strlen($cep) != 8) {
            return false;
        }

        $adress = getAdressByCep($cep);
        
        if(pr_value($adress, 'erro')) {
            return false;
        }
        
        return true;
    }

    public static function getAdressByLikeParams($params) {
      $adress = (new qbquery('users'))
      ->whereLike($params)
      ->getMany();

      return $adress;
  }

  public static function getDataAdressUpdate($adress, $data) {
    $street_adress  = $data->street_adress;
    $residence_code = $data->residence_code;
    $country        = $data->country;
    $city           = $data->city;
    $district       = $data->district;  
    $state          = $data->state;
    $cep            = fmt_cep($data->cep);  
    $complement     = $data->complement; 

    $adress->street_adress   = trim($street_adress)  ? $street_adress  : $adress->street_adress; 
    $adress->residence_code  = trim($residence_code) ? $residence_code : $adress->residence_code;
    $adress->country         = trim($country)        ? $country        : $adress->country ;
    $adress->city            = trim($city)           ? $city           : $adress->city ;
    $adress->district        = trim($district)       ? $district       : $adress->district;
    $adress->state           = trim($state)          ? $state          : $adress->state;
    $adress->cep             = trim($cep)            ? $cep            : $adress->cep;
    $adress->complement      = trim($complement)     ? $complement     : $adress->complement ;

    return $adress;
  }

  public static function getManyAdress() {
    return (new qbquery('adress'))
     ->getMany();
  }
}