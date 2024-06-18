<?php
require_once(__DIR__ . '/cart.service.php'); 

class CartController {
    public function create() {
        $body = requestBody();
        CartService::create(data : $body);
    }

    public function show() {
        $params = getRouteParams();
        $response = CartService::show(params : $params);
        responseJson($response);
    }

    public function update() {
        $id = getRouteParams();
        $body = requestBody();
        CartService::update(id : $id, data: $body);
    }

    public function delete() {
        $id = getRouteParams();
        CartService::delete(id : $id);
    }
}