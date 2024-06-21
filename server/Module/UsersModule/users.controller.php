<?php
require_once(__DIR__ . '/users.service.php'); 
require_once(dirname(dirname(__DIR__)) . '/middleware/authMiddleware.php');

class UsersController {
    
    public function create() {
        $body = requestBody();

        UserService::create(data : $body);
    }

    public function update() {
        $id   = getRouteParams('id');
        $body =  requestBody(); 
        UserService::update(id : $id, data : $body);
    }

    public function show() {
        $params = getRouteParams();
        $response = UserService::show(params : $params);
        responseJson($response);
    }
}

?>