<?php 
function AppError($message, $statusCode = 500) {
    $exception = new Exception($message, $statusCode);

    http_response_code($statusCode);
    return  [
        "error"   => $statusCode,
        "message" => $exception->getMessage()
    ];
}


function AppSucess($message, $statusCode = 200) {
    http_response_code($statusCode);
    return  [
        "statusCode"   => $statusCode,
        "message"      => $message
    ];
}

?>