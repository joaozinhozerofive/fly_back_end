<?php
require_once(__DIR__ . '/categories.service.php');

class CategoriesController {
    public function create() {
        if(!isAuthenticated()) {
            die;
        }

        $body = requestBody();
        CategoriesService::create(data: $body);
    }

    public function show() {
        $params = getRouteParams();

        $response = CategoriesService::show(params : $params);
        response($response);
    }

    public function update() {
        if(!isAuthenticated()) {
            die;
        }

        $id   = getRouteParams('id');
        $body = requestBody();
        CategoriesService::update(id : $id, data: $body);
    }

    public function delete() {
        $id   = getRouteParams('id');
        CategoriesService::delete(id : $id);
    }
}
