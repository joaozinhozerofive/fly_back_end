<?php 
require_once(__DIR__ . '/products.service.php');
class ProductsController{
    public function create() {
        $body  = requestBody();
        
        ProductsService::create(data:  $body);
    }

    public function show() {
        
        $params   = getRouteParams();
        $response = ProductsService::show(params : $params);

        responseJson($response);
    }

    public function update() {
        $body = requestBody();
        $id = getRouteParams('id');
        ProductsService::update(id: $id, data: $body);
    }
}