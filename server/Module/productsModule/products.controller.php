<?php 
require_once(__DIR__ . '/products.service.php');
class ProductsController{
    public function create() {
        if(!isAuthenticated()) {
            die;
        }

        $body  = requestBody();
        
        ProductsService::create(data:  $body);
    }

    public function show() {

    }

    public function update() {
        if(!isAuthenticated())  {
            die;
        }       
    }
}