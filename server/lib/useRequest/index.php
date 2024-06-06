<?php 
function requestBody(){
    $requestBody = file_get_contents('php://input'); 
    $data    =  json_decode($requestBody);
    $body    =  new stdClass();

    if(!$requestBody){
        response(AppError('Não existe corpo para esta requisição', 500));
        die;
    }

    foreach($data as $key => $value) {
        $body->$key = $value;
    }

    return  $body;
}

function response($response) {
    $response = json_encode($response);

    echo $response;
}

function getRouteParams($param = null) {
    if($param) {
      if(array_key_exists($param, $_GET)) {
         return $_GET[$param];
      }
      else {
        return null;
      }
    }
    else {
        return $_GET;
    }
    
}



?>