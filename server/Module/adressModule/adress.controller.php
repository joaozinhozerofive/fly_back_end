<?php
require_once(__DIR__ . '/adress.service.php'); 


class AdressController{
    public function create() {
        $body = requestBody();
        $response = AdressService::create(data : $body);
        responseJson($response);
    }

    public function show() {
        $params = getRouteParams();
        $response = AdressService::show(params : $params);
        responseJson($response);
    }

    public function update() {
        $id = getRouteParams('id');
        $body = requestBody();
        AdressService::update(id : $id, data: $body);
    }

    public function delete(){
        $id = getRouteParams('id');
        AdressService::delete(id : $id);
    }
}