<?php

require_once __DIR__ . "/../models/booking.php";
require_once __DIR__ . "/../models/have.php";

$bookingModel = new Booking();											//new instance of class user from user.php
$haveModel = new Have();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	if (isset($_POST['bookingByDay'])) {

		$date = substr($_POST['date'], 0, strpos($_POST['date'], '('));
		$date = date("Y-m-d", strtotime($date));

		$response = $bookingModel->getBookingByDay($date);

		if ($response["success"]) {
			if (!empty($response["data"])) {
				echo json_encode($response);
			} else {
				$response = array("success" => TRUE, 'data' => array());
				echo json_encode($response);
			}
		} else {
			$response = array("success" => FALSE);
			echo json_encode($response);
		}

	} elseif (isset($_POST['bookingByWeek'])) {

		$startDate = substr($_POST['startDate'], 0, strpos($_POST['startDate'], '('));
		$startDate = date("Y-m-d", strtotime($startDate));
		$endDate = substr($_POST['endDate'], 0, strpos($_POST['endDate'], '('));
		$endDate = date("Y-m-d", strtotime($endDate));

		$response = $bookingModel->getBookingByInterval($startDate, $endDate);

		if ($response["success"]) {
			if (!empty($response["data"])) {
				echo json_encode($response);
			} else {
				$response = array("success" => TRUE, 'data' => array());
				echo json_encode($response);
			}
		} else {
			$response = array("success" => FALSE);
			echo json_encode($response);
		}

	} else {

	$fix_type = filter_input(INPUT_POST, "selectType", FILTER_SANITIZE_STRING);
	$details = filter_input(INPUT_POST, "details", FILTER_SANITIZE_STRING);
	$date = filter_input(INPUT_POST, "date", FILTER_SANITIZE_STRING);
	$vehicle_id = filter_input(INPUT_POST, "vehID", FILTER_SANITIZE_NUMBER_INT);

	$date = date("Y-m-d", strtotime($date));

	$response = $bookingModel->createBooking($fix_type, $details, $date);

	if ($response["success"]) {
		if (!empty($response["bookingID"])) {
			$booking_id = $response["bookingID"];
			$response = $haveModel->addHaveBooking($vehicle_id, $booking_id);
			echo json_encode($response);
		} else {
			$response = array("success" => FALSE);
			echo json_encode($response);
		}
	} else {
		$response = array("success" => FALSE);
		echo json_encode($response);
	}
	}
	

}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	$response = $bookingModel->checkBookingsWeight();
	if ($response["success"]) {
		echo json_encode($response);
	} else {
		$response = array("success" => FALSE);
		echo json_encode($response);
	}
}
