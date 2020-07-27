<?php

require_once __DIR__ . "/../models/cost.php";
require_once __DIR__ . "/../models/booking.php";

class Invoice {

    private $costModel;
    private $bookingModel;

    public function __construct()
    {
        $this->costModel = new Cost();
        $this->bookingModel = new Booking();
    }


public function getBookingById($booking_id){


    $response = $this->bookingModel->getBookingById($booking_id);

    if($response ["success"]){
        if(!empty($response["data"])){
            return $response["data"][0];
        } else {
            return $response;
        }
    } else {
        return $response;
    }
}

public function getCosts($booking_id){
    $response = $this->costModel->getCosts($booking_id);

    if($response ["success"]){
        if(!empty($response["data"])){
            return $response["data"];
        } else {
            $response["success"] = false;
            return $response;
        }
    } else {
        return $response;
    }
}
}
