<?php 
function request(){
    $requestBody    = file_get_contents('php://input'); 
    $data    = json_decode($requestBody);
    $body =  new stdClass();

    foreach($data as $key => $value) {
        $body->$key = $value;
    }

    return $body;
}

function responseBody(stdClass $response) {
    $response = json_encode($response);

    return $response;
}



?>