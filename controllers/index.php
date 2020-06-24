<?php

use function PHPSTORM_META\type;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if(isset($_POST["login"])){
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  } else {
    $request = filter_var_array(json_decode(file_get_contents('php://input'), true), [
    "fname" => FILTER_SANITIZE_STRING,
    "surname" => FILTER_SANITIZE_STRING,
    "address" => FILTER_SANITIZE_STRING,
    "phone" => FILTER_SANITIZE_STRING,
    "sp_email" => FILTER_SANITIZE_STRING,
    "sp_password" => FILTER_SANITIZE_STRING
     ]);

     
    }
}
