<?php
require_once(__DIR__ . '/orders.service.php'); 

class OrdersController{
    public function create() {
        $body = requestBody();
        OrdersService::create(data : $body);
    }

    public function show() {
        $params = getRouteParams();
        $response = OrdersService::show(params : $params);
        responseJson($response);
    }

    public function update() {
        $id = getRouteParams('id');
        $data = requestBody();
        OrdersService::update(id : $id, data: $data);
    }
}
