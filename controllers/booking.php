<?php

require_once __DIR__ . "/../models/booking.php";
require_once __DIR__ . "/../models/have.php";
require_once __DIR__ . "/../models/cost.php";
require_once __DIR__ . "/../models/assign.php";
require_once __DIR__ . "/../controllers/invoice.php";

$invoiceController = new Invoice();
$bookingModel = new Booking();											//new instance of class user from user.php
$haveModel = new Have();
$costModel = new Cost();
$assignModel = new Assign();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (isset($_POST['bookingByDay'])) {

		$date = substr($_POST['date'], 0, strpos($_POST['date'], '('));
		$date = date("Y-m-d", strtotime($date));

		$response = $bookingModel->getBookingByDay($date);

		if ($response["success"]) {
			if (!empty($response["data"])) {
				for ($j = 0; $j < count($response["data"]); $j++) {
					$costs_info = $invoiceController->getCosts($response["data"][$j]["booking_id"]);
					$total = 0;
					if (!isset($costs_info["success"])) {

						for ($i = 0; $i < count($costs_info); $i++) {
							if (isset($costs_info[$i]["part_id"])) {
								$sum_part = intval($costs_info[$i]["part_cost"]) * intval($costs_info[$i]["quantity"]);
								$total += $sum_part;
							} else {
								$total += intval($costs_info[$i]["cost"]);
							}
						}
					}

					$response["data"][$j]["cost"] = $total;
				}

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
	} elseif (isset($_POST['editBooking'])) {


		$cost = filter_input(INPUT_POST, "costInput", FILTER_SANITIZE_STRING);
		$description = filter_input(INPUT_POST, "details", FILTER_SANITIZE_STRING);
		$staff_id = filter_input(INPUT_POST, "selectStaff", FILTER_SANITIZE_STRING);
		$booking_id = filter_input(INPUT_POST, "bookID", FILTER_SANITIZE_STRING);
		$status = filter_input(INPUT_POST, "selectStatus", FILTER_SANITIZE_STRING);
		$number_parts = filter_input(INPUT_POST, "qtn", FILTER_SANITIZE_NUMBER_INT);

		$response = $costModel->addCost($cost, $description, NULL, NULL, $booking_id);

		for ($i = 0; $i < $number_parts; $i++) {
			$quantity = filter_input(INPUT_POST, "quantity_" . $i, FILTER_SANITIZE_NUMBER_INT);
			$part_id = filter_input(INPUT_POST, "part_" . $i, FILTER_SANITIZE_NUMBER_INT);

			$costModel->addCost(NULL, NULL, $part_id, $quantity, $booking_id);
		}

		$response = $assignModel->deleteStaff($booking_id);
		if ($response["success"]) {
			$response = $assignModel->assignStaff($staff_id, $booking_id);
			if ($response["success"]) {
				$response = $bookingModel->editStatus($booking_id, $status);
				echo json_encode($response);
			} else {
				echo json_encode($response);
			}
		} else {
			echo json_encode($response);
		}
	} elseif (isset($_POST['history'])) {

		$vehicle_id = intval($_POST['vehicle_id']);
		$response = $bookingModel->getHistory($vehicle_id);

		if ($response["success"]) {
			if (!empty($response["data"])) {
				for ($j = 0; $j < count($response["data"]); $j++) {
					$costs_info = $invoiceController->getCosts($response["data"][$j]["booking_id"]);
					$total = 0;
					if (!isset($costs_info["success"])) {

						for ($i = 0; $i < count($costs_info); $i++) {
							if (isset($costs_info[$i]["part_id"])) {
								$sum_part = intval($costs_info[$i]["part_cost"]) * intval($costs_info[$i]["quantity"]);
								$total += $sum_part;
							} else {
								$total += intval($costs_info[$i]["cost"]);
							}
						}
					}

					$response["data"][$j]["cost"] = $total;
				}
				echo json_encode($response);
			} else {
				$response = array("success" => FALSE);
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
