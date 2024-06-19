<?php
require_once(__DIR__ . '/subcategories.service.php');
class SubcategoriesController {
    public function create() {
        $body = requestBody();
        SubcategoriesService::create(data: $body);
    }

    public function show() {
        $params   = getRouteParams();
        $response =  SubcategoriesService::show(params: $params);
        responseJson($params);
    }

    public function update() {
        $id = getRouteParams('id');
        $body = requestBody();
        SubcategoriesService::update(id: $id, data: $body);
    }
  
}