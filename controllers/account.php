<?php

require_once __DIR__ . "/../models/vehicle_details.php";
require_once __DIR__ . "/../models/vehicle.php";
require_once __DIR__ . "/../models/own.php";
require_once __DIR__ . "/../models/user.php";

$userModel = new User();
$userID = $userModel->getUserCookie();

if (isset($userID)) {
  if (!$userModel->checkUserSession($userID)) {
    echo "<script> alert ('Please Login First');
    window.location = '../index.php'</script>";
  }
} else {
  echo "<script> alert ('Please Login First');
  window.location = '../index.php'</script>";
}

$vehicleDetailsModel = new vehicleDetails();                                            //new instance of class user from user.php
$vehicleModel = new Vehicle();
$ownModel = new Own();


if ($_SERVER['REQUEST_METHOD'] == 'GET') {                            //

    $response = $vehicleDetailsModel->getVehicleDetails();

    if ($response["success"]) {
        echo json_encode($response);
    } else {
        $response = array("success" => FALSE);
        echo json_encode($response);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {							//
	

		$licence_details = filter_input(INPUT_POST, 'licence', FILTER_SANITIZE_EMAIL);
        $engine = filter_input(INPUT_POST, 'selectEngine', FILTER_SANITIZE_STRING);
		$vehicle_details_id = filter_input(INPUT_POST, 'vehicle_details_id', FILTER_SANITIZE_EMAIL);
		
		$response = $vehicleModel->addVehicle($licence_details, $engine, $vehicle_details_id);
		
		if($response["success"]){
			if (!empty($response["vehicleId"])) {
				$vehicleId = $response["vehicleId"];
				$response = $ownModel->addOwnership($userID, $vehicleId);
				echo json_encode($response);
			} else {
				$response = array("success" => FALSE);
				echo json_encode($response);
			}
		}else {
			$response = array("success" => FALSE);
			echo json_encode($response);
		}
}
