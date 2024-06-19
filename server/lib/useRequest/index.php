<?php 
function requestBody(){
    $requestBody = file_get_contents('php://input'); 
    $data    =  json_decode($requestBody);
    $body    =  new stdClass();

    if(!$data) {
        responseJson(AppError("JSON Inválido.", 500));
    }

    if(!$requestBody){
        responseJson(AppError('Não existe corpo para esta requisição', 500));
    }

    foreach($data as $key => $value) {
        $body->$key = $value;
    }

    return  $body;
}

function responseJson($response) {
    $response = json_encode($response);

    echo $response;
}

function responseStaticFile($fileName) {
    $file_path = dirname(dirname(__DIR__)) . "/TMP/$fileName";

    if(file_exists($file_path)) {
        header('Content-Type: image/jpeg');
        readfile($file_path);
    }
}

function getRouteParams($param = null) {
    if($param) {
      if(array_key_exists($param, $_GET)) {
         return $_GET[$param];
      }
      else {
        $_GET[$param] = "";
        return $_GET;
      }
    }
    else {
        $param = new stdClass(); 

        foreach($_GET as $key => $value) {
            $param->$key = $value;
        }

        return $param;
    }
}

function getFiles() {
    $files =  new stdClass();
    $data = json_encode($_FILES);
    $data = json_decode($data);

    if($data) {
        return $data;
    }

    return new stdClass();
}

function requestFormData() {
    $data = json_encode($_POST);
    $data = json_decode($data);

    return $data; 
}
?>