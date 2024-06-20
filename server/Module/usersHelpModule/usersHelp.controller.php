<?php 
require_once(__DIR__ . '/usersHelp.service.php');
class UsersHelpController {
    public function create() {
        $body =  requestBody();
        UsersHelpService::create(data: $body);
    }
}