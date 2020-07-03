<?php

class VehicleDetails
{
    private $connection;

    private function connect($host, $user, $password, $db)                      //connect DB function
    {
        try {
            $this->connection = mysqli_connect($host, $user, $password, $db);      //connect
        } catch (Exception $e) {
            print_r($e->getMessage());                                             //if error, print message
            die();                                                                 // stop function
        }
    }

    private function disconnect()                                                  // disconect db function
    {
        mysqli_close($this->connection);
    }

    public function getVehicleDetails(){

        $this->connect("localhost", "root", "", "db_garage");                               //connect to db

        $stmt = $this->connection->prepare("SELECT * FROM db_garage.vehicle_details");
        
        if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);  
        $this->disconnect();
        return array("success" => TRUE, "data" => $result);  
        } else {
            $this->disconnect();                                                                        //if query dont work (what comes from dp?) disconnect
            return array("success" => FALSE);                                                           //return array ['success' = false
        }
    }

    
}
