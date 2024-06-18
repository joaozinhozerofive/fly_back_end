<?php
require_once(__DIR__ . '/categories.service.php');

class CategoriesController {
    public function create() {
        $body = requestBody();
        CategoriesService::create(data: $body);
    }

    public function show() {
        $params = getRouteParams();

        $response = CategoriesService::show(params : $params);
        responseJson($response);
    }

    public function update() {
        $id   = getRouteParams('id');
        $body = requestBody();
        CategoriesService::update(id : $id, data: $body);
    }
}
