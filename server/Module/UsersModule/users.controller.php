<?php
require_once(__DIR__ . '/users.service.php'); 
require_once(dirname(dirname(__DIR__)) . '/middleware/authMiddleware.php');

class UsersController {
    
    public function create() {
        $body = requestBody();

        UserService::create(data : $body);
    }

    public function update() {
        if(!isAuthenticated()) {
            die;
        }

        $id   = getRouteParams('id');
        $body =  requestBody(); 
        UserService::update(id : $id, data : $body);
    }

    public function show() {
        if(!isAuthenticated()) {
            die;
        }

        $params = getRouteParams();
        $response = UserService::show(params : $params);
        response($response);
    }

    public function delete() {
        if(!isAuthenticated()) {
            die;
        }
        
        $id = intval(getRouteParams('id'));   
        UserService::delete(id : $id);
    }
}

?>