<?php

require_once __DIR__ . "/../models/parts.php";

$partsModel = new Parts();

$response = $partsModel->getParts();

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