<?php 
function AppError($message, $statusCode = 500) {
    $exception = new Exception($message, $statusCode);

    http_response_code($statusCode);
    
    response([
        "error"   => $statusCode,
        "message" => $exception->getMessage()
    ]);

    die;
}

function AppAlerta($message, $statusCode = 500) {
    $exception = new Exception($message, $statusCode);

    http_response_code($statusCode);
    return  response([
        "error"   => $statusCode,
        "message" => $exception->getMessage()
    ]);
}


function AppSucess($message, $statusCode = 200) {
    http_response_code($statusCode);
    response([
        "message"      => $message
    ]);

    die;
}

?>