<?php

require_once __DIR__ . "/../models/user.php";

$userModel = new User();

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

    $fname = $request["fname"];
    $surname = $request["surname"];
    $address = $request["address"];
    $phone = $request["phone"];
    $sp_email = $request["sp_email"];
    $sp_password = $request["sp_password"];


    $response = $userModel->createUser($fname, $surname, $address, $phone, $sp_email, $sp_password);
      echo json_encode($response);
    
    }
}
