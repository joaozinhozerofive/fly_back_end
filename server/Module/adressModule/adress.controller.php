<?php
require_once(__DIR__ . '/adress.service.php'); 


class AdressController{
    public function create() {
        if(!isAuthenticated()){
            die;
        }

        $body = requestBody();
        AdressService::create(data : $body);
    }

    public function show() {
        if(!isAuthenticated()){
            die;
        }

        $params = getRouteParams();
        $response = AdressService::show(params : $params);
        response ($response);
    }

    public function update() {
        if(!isAuthenticated()){
            die;
        }

        $id = getRouteParams('id');
        $body = requestBody();
        AdressService::update(id : $id, data: $body);
    }

    public function delete(){
        if(!isAuthenticated()){
            die;
        }

        $id = getRouteParams('id');
        AdressService::delete(id : $id);
    }
}