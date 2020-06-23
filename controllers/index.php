<?php

use function PHPSTORM_META\type;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
}
?>