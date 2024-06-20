<?php

require_once(__DIR__ . '/showcase.service.php');

class ShowcaseController {
    public function create() {
        $body = requestBody();
        ShowcaseService::create(data : $body);
    }


    public function show() {
        $params   = getRouteParams();
        $response = ShowcaseService::show(params : $params);

        responseJson($response);
    }

    public function update() {
        $id   = getRouteParams('id');
        $body = requestBody();
        ShowcaseService::update(id : $id, data : $body);
    }

    public function delete() {

    }
}