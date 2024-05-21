<?php

function allowCors() {
    $origem_permitida = 'http://www.exemplo2.com';
    header("Access-Control-Allow-Origin: *");

    // Permitir solicitações com os métodos GET, POST, PUT, DELETE e OPTIONS
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    // Permitir solicitações com os cabeçalhos "Content-Type" e "Authorization"
    header("Access-Control-Allow-Headers: Content-Type, Authorization, multipart/form-data");

    // Definir o tempo máximo de vida (em segundos) da requisição preflight (pré-requisição)
    header("Access-Control-Max-Age: 3600");

    // Verificar se a requisição é uma pré-requisição OPTIONS
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        // Retornar com status 200 OK para a pré-requisição OPTIONS
        exit();
    }
}
?>
