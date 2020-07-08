<?php

require_once __DIR__ . "/../models/booking.php";
require_once __DIR__ . "/../models/have.php";

$bookingModel = new Booking();											//new instance of class user from user.php
$haveModel = new Have();

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

		$fix_type = filter_input(INPUT_POST, "selectType", FILTER_SANITIZE_STRING);
		$details = filter_input(INPUT_POST, "details", FILTER_SANITIZE_STRING);
		$date = filter_input(INPUT_POST, "date", FILTER_SANITIZE_STRING);
		$vehicle_id = filter_input(INPUT_POST, "vehID", FILTER_SANITIZE_NUMBER_INT);

		$date=date("Y-m-d", strtotime($date));

		$response = $bookingModel->createBooking($fix_type, $details, $date);
		
		if($response["success"]){
			if (!empty($response["bookingID"])) {
				$booking_id = $response["bookingID"];
				$response = $haveModel->addHaveBooking($vehicle_id, $booking_id);
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