<?php
require_once(__DIR__ . '/subcategories.service.php');
class SubcategoriesController {
    public function create() {
        if(!isAuthenticated()) {
            die;
        }
        $body = requestBody();
        SubcategoriesService::create(data: $body);
    }

    public function show() {
        $params   = getRouteParams();
        $response =  SubcategoriesService::show(params: $params);
        response($response);
    }

    public function update() {
        if(!isAuthenticated()) {
            die;
        }

        $id = getRouteParams('id');
        $body = requestBody();
        SubcategoriesService::update(id: $id, data: $body);
    }

    public function delete() {
        if(!isAuthenticated()) {
            die;
        }
    }
}