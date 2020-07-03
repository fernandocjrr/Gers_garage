<?php

require_once __DIR__ . "/../models/vehicle_details.php";

$vehicleDetailsModel = new vehicleDetails();                                            //new instance of class user from user.php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {                            //


    $response = $vehicleDetailsModel->getVehicleDetails();


    if ($response["success"]) {
        echo json_encode($response);
    } else {
        $response = array("success" => FALSE);
        echo json_encode($response);
    }
}
