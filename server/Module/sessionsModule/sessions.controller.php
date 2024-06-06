<?php
require_once(__DIR__ . '/sessions.service.php'); 

class SessionsController {
    
    public function create() {
      $body = requestBody();
      
      $response = SessionsService::create($body);
      
      response($response);
    }

    
}

?>