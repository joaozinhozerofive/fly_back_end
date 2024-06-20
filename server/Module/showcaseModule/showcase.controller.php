<?php

require_once(__DIR__ . '/showcase.service.php');

class ShowcaseController {
    public function create() {
        $body = requestBody();
        ShowcaseService::create(data : $body);
    }


    public function show() {
        
    }

    public function update() {

    }

    public function delete() {

    }
}