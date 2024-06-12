<?php 
function AppError($message, $statusCode = 500) {
    $exception = new Exception($message, $statusCode);

    http_response_code($statusCode);
    
    responseJson([
        "error"   => $statusCode,
        "message" => $exception->getMessage()
    ]);

    die;
}

function AppAlerta($message, $statusCode = 500) {
    $exception = new Exception($message, $statusCode);

    http_response_code($statusCode);
    return  responseJson([
        "error"   => $statusCode,
        "message" => $exception->getMessage()
    ]);
}


function AppSucess($message, $statusCode = 200) {
    http_response_code($statusCode);
    responseJson([
        "message"      => $message
    ]);

    die;
}

?>