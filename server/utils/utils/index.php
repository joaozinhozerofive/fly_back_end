<?php

function pr_value($object, $propertyName) {
  if(property_exists($object, $propertyName) && !empty(trim($object->$propertyName))) {
    return $object->$propertyName;
  }

  return null;
}

function fmt_cpf(string $cpf) {
  $cpf = preg_replace('/[^0-9]/', '', $cpf);
  return trim($cpf);
}
function fmt_cep(string $cep) {
  $cep = preg_replace('/[^0-9]/', '', $cep);
  return trim($cep);
}

function fmt_phone($phone) {
  $phone = preg_replace('/[^0-9]/', '', $phone);
  return trim($phone);
}

function fmt_data($date) {
  $date = preg_replace('/[^0-9]/', '-', $date);
  $date = trim($date);
  return $date;
}

 
function getData(string $table, array $condition = null) {
  if(!$condition) {
    $data =  (new qbquery($table))
    ->getMany();
  }
  else {
    $data =  (new qbquery($table))
    ->where($condition)
    ->getFirst();
  }
  
  if($data) {
      return $data;
  }

  return false;
}

function checkEmail($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function getUserId() {
  return $_REQUEST['user_id'];
}

function array_db_toPHP(string  $arrayDb) {
  $arrayDb = str_replace("}", "", $arrayDb);
  $arrayDb = str_replace("{", "", $arrayDb);

  return explode(",", $arrayDb);
} 


function array_php_toDb(array $arrayPHP) {
  $string = implode(',', $arrayPHP);
  $string = "{".$string ."}";

  return $string;
} 
?>
