<?php

class Booking
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

    public function createBooking($fix_type, $details, $date)
    {
        $this->connect("localhost", "root", "", "db_garage");

        $stmt = $this->connection->prepare("INSERT INTO booking (fix_type, details, date)
        VALUES (?,?,?)");                 //????

        if ($stmt) {
            $stmt->bind_param("sss", $fix_type, $details, $date);
            $stmt->execute(); 
            $bookingId = $this->connection->insert_id;
            $this->disconnect();
            return array("success" => TRUE, "bookingID" => $bookingId);
        } else {
            $this->disconnect();
            return array("success" => FALSE);
        }
    }

    public function checkBookingsWeight ()
    {
        $this->connect("localhost", "root", "", "db_garage");

        $stmt = $this->connection->prepare("SELECT t.date, SUM(t.weight) AS total FROM
        (
            SELECT booking.*,
                 CASE
                     WHEN fix_type = \"major service\" THEN 2
                     WHEN fix_type = \"major repair\" THEN 2
                     WHEN fix_type = \"anual service\" THEN 1
                     ELSE 1
                  END AS weight
            FROM booking
        ) AS t GROUP BY date");

        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $this->disconnect();
            return array("success" => TRUE, "data" => $result);
        } else {
            $this->disconnect();
            return array("success" => FALSE);
        }
    }

    public function getBookingByDay ($date)
    {
        $this->connect("localhost", "root", "", "db_garage");

        $stmt = $this->connection->prepare("SELECT * FROM booking  WHERE date = ?");

        if ($stmt) {
            $stmt->bind_param("s", $date);
            $stmt->execute(); 
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $this->disconnect();
            return array("success" => TRUE, "data" => $result);
        } else {
            $this->disconnect();
            return array("success" => FALSE);
        }
    }

    public function getBookingByInterval ($startDate, $endDate)
    {
        $this->connect("localhost", "root", "", "db_garage");

        $stmt = $this->connection->prepare("SELECT * FROM booking  WHERE date >= ? AND date <= ?");

        if ($stmt) {
            $stmt->bind_param("ss", $startDate, $endDate);
            $stmt->execute(); 
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $this->disconnect();
            return array("success" => TRUE, "data" => $result);
        } else {
            $this->disconnect();
            return array("success" => FALSE);
        }
    }

}