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
    }

    public function update() {
        if(!isAuthenticated()){
            die;
        }
    }

    public function delete() {
        if(!isAuthenticated()){
            die;
        }
    }
}
