<?php
require_once(__DIR__ . '/cart.service.php'); 

class CartController {
    public function create() {
        if(!isAuthenticated()){
            die;
        }

        $body = requestBody();
        CartService::create(data : $body);
    }

    public function show() {
        if(!isAuthenticated()){
            die;
        }

        $params = getRouteParams();
        $response = CartService::show(params : $params);
        response($response);
    }

    public function update() {
        if(!isAuthenticated()){
            die;
        }
        $id = getRouteParams();
        $body = requestBody();
        CartService::update(id : $id, data: $body);
    }

    public function delete() {
        if(!isAuthenticated()){
            die;
        }

        $id = getRouteParams();
        CartService::delete(id : $id);
    }
}