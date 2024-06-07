<?php
require_once(__DIR__ . '/orders.service.php'); 

class OrdersController{
    public function create() {
        if(!isAuthenticated()){
            die;
        }
        $body = requestBody();
        OrdersService::create(data : $body);
    }

    public function show() {
        if(!isAuthenticated()){
            die;
        }
        $params = getRouteParams();
        $response = OrdersService::show(params : $params);
        response($response);
    }

    public function update() {
        if(!isAuthenticated()){
            die;
        }
        $id = getRouteParams('id');
        $data = requestBody();
        OrdersService::update(id : $id, data: $data);
    }
}
