<?php

require_once __DIR__ . "/../models/user.php";

$userModel = new User();											//new instance of class user from user.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {							//
	if (isset($_POST["login"])) {

		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

		$response = $userModel->login($email, $password);
		
		
		if ($response["success"]) {
			if (empty($response["data"])) {
				$response = array("success" => FALSE, "wrong" => TRUE);
				echo json_encode($response);
			} else {
				$userModel->setUserSessionAndCookie($response["data"][0]["user_id"]);
				$response = array("success" => TRUE);
				echo json_encode($response);
			}
		} else {
			$response = array ("success" => FALSE);
			echo json_encode($response);
		}
	} else {
		$fname = filter_input(INPUT_POST, "fname", FILTER_SANITIZE_STRING);
		$surname = filter_input(INPUT_POST, "surname", FILTER_SANITIZE_STRING);
		$address = filter_input(INPUT_POST, "address", FILTER_SANITIZE_STRING);
		$phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_STRING);
		$sp_email = filter_input(INPUT_POST, "sp_email", FILTER_SANITIZE_EMAIL);
		$sp_password = filter_input(INPUT_POST, "sp_password", FILTER_SANITIZE_STRING);

		$response = $userModel->checkUser($sp_email);
		if ($response["success"]) {
			if (empty($response["data"])) {
				$response = $userModel->createUser($fname, $surname, $address, $phone, $sp_email, $sp_password);
				echo json_encode($response);
			} else {
				$response = array("success" => FALSE, "exists" => TRUE);
				echo json_encode($response);
			}
		} else {
			$response = array("success" => FALSE);
			echo json_encode($response);
		}
	}
}
