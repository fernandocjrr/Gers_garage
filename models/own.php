<?php

class Own
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

    public function addOwnership($userId, $vehicleId) //sign in function
    {
        $this->connect("localhost", "root", "", "db_garage");                               //connect to db

        $stmt = $this->connection->prepare("INSERT INTO own (user_id,vehicle_id)
        VALUES (?,?)");                 //????

        if ($stmt) {                                                                                    //if stmt succsessful
            $stmt->bind_param("ii", $userId, $vehicleId);   //replace ? for parameter
            $stmt->execute();                                                                        //execute query
            $this->disconnect();                                                                        //disconect from d
            return array("success" => TRUE);                                              //return array ['success' = true]
        } else {
            $this->disconnect();                                                                //if query dont work (what comes from dp?) disconnect
            return array("success" => FALSE);                                                           //return array ['success' = false
        }
    }

    public function getVehiclesByUser($userId) //sign in function
    {
        $this->connect("localhost", "root", "", "db_garage");                               //connect to db

        $stmt = $this->connection->prepare("SELECT * FROM own 
                                                INNER JOIN vehicle ON own.vehicle_id = vehicle.vehicle_id
                                                INNER JOIN vehicle_details ON vehicle.vehicle_details_id = vehicle_details.vehicle_details_id
                                                WHERE user_id = ?");                 //????

        if ($stmt) {                                                                                    //if stmt succsessful
            $stmt->bind_param("i", $userId);   //replace ? for parameter
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);                                                                        //execute query
            $this->disconnect();                                                                        //disconect from d
            return array("success" => TRUE, "data" => $result);                                              //return array ['success' = true]
        } else {
            $this->disconnect();                                                                //if query dont work (what comes from dp?) disconnect
            return array("success" => FALSE);                                                           //return array ['success' = false
        }
    }

    
}