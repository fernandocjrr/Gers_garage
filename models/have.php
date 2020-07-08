<?php

class Have
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

    public function addHaveBooking($vehicle_id, $booking_id) //sign in function
    {
        $this->connect("localhost", "root", "", "db_garage");                               //connect to db

        $stmt = $this->connection->prepare("INSERT INTO have (vehicle_id, booking_id)
        VALUES (?,?)");                 //????

        if ($stmt) {                                                                                    //if stmt succsessful
            $stmt->bind_param("ii", $vehicle_id, $booking_id);   //replace ? for parameter
            $stmt->execute();                                                                           //execute query
            $this->disconnect();                                                                        //disconect from db
            return array("success" => TRUE);                                                            //return array ['success' = true]
        } else {
            $this->disconnect();                                                                        //if query dont work (what comes from dp?) disconnect
            return array("success" => FALSE);                                                           //return array ['success' = false
        }
    }


}