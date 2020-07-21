<?php

require_once __DIR__ . "/../models/staff.php";

$staffModel = new Staff();

$response = $staffModel->getStaffs();

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

?>