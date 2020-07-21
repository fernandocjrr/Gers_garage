<?php

class Assign
{
    private $connection;

    private function connect($host, $user, $password, $db)
    {
        try {
            $this->connection = mysqli_connect($host, $user, $password, $db);
        } catch (Exception $e) {
            print_r($e->getMessage());
            die();
        }
    }

    private function disconnect()
    {
        mysqli_close($this->connection);
    }

    public function assignStaff($staff_id, $booking_id){

        $this->connect("localhost", "root", "", "db_garage");

        $stmt = $this->connection->prepare("INSERT INTO assign (staff_id, booking_id)
        VALUES (?,?)");                 //????

        if ($stmt) {
            $stmt->bind_param("ii", $staff_id, $booking_id);
            $stmt->execute(); 
            $this->disconnect();
            return array("success" => TRUE);
        } else {
            $this->disconnect();
            return array("success" => FALSE);
        }
    } 

}
?>