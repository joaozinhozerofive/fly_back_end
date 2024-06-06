<?php
function getAdressByCep($cep) {
    $url = "viacep.com.br/ws/$cep/json/";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $adress = curl_exec($ch);
    curl_close($ch);
    return json_decode($adress);
}