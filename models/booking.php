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

        $stmt = $this->connection->prepare("SELECT booking.* , vehicle.*, vehicle_details.*, user.*, assign.staff_id , staff.*
                                                                    FROM booking   
                                                                    INNER JOIN have ON booking.booking_id = have.booking_id
                                                                    INNER JOIN vehicle ON vehicle.vehicle_id = have.vehicle_id
                                                                    INNER JOIN vehicle_details ON vehicle.vehicle_details_id = vehicle_details. vehicle_details_id
                                                                    INNER JOIN own ON own.vehicle_id = vehicle.vehicle_id
                                                                    INNER JOIN user ON own.user_id = user.user_id
                                                                    LEFT JOIN assign ON booking.booking_id = assign.booking_id
                                                                    LEFT JOIN staff ON staff.staff_id = assign.staff_id 
                                                                     
                                                                    WHERE date = ?");

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

        $stmt = $this->connection->prepare("SELECT booking.* , vehicle.*, vehicle_details.*, user.*, assign.staff_id , staff.*
                                                                    FROM booking   
                                                                    INNER JOIN have ON booking.booking_id = have.booking_id
                                                                    INNER JOIN vehicle ON vehicle.vehicle_id = have.vehicle_id
                                                                    INNER JOIN vehicle_details ON vehicle.vehicle_details_id = vehicle_details. vehicle_details_id
                                                                    INNER JOIN own ON own.vehicle_id = vehicle.vehicle_id
                                                                    INNER JOIN user ON own.user_id = user.user_id
                                                                    LEFT JOIN assign ON booking.booking_id = assign.booking_id
                                                                    LEFT JOIN staff ON staff.staff_id = assign.staff_id
                                                                     
                                                                    WHERE date >= ? AND date <= ?");

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

    public function editStatus ($bookingId, $status)
    {
        $this->connect("localhost", "root", "", "db_garage");

        $stmt = $this->connection->prepare("UPDATE booking SET status = ? WHERE booking_id = ?");

        if ($stmt) {
            $stmt->bind_param("si", $status, $bookingId);
            $stmt->execute();
            $this->disconnect();
            return array("success" => TRUE);
        } else {
            $this->disconnect();
            return array("success" => FALSE);
        }
    }


    public function getBookingById ($bookingId)
    {
        $this->connect("localhost", "root", "", "db_garage");

        $stmt = $this->connection->prepare("SELECT * FROM booking   INNER JOIN have ON booking.booking_id = have.booking_id
                                                                    INNER JOIN vehicle ON vehicle.vehicle_id = have.vehicle_id
                                                                    INNER JOIN vehicle_details ON vehicle.vehicle_details_id = vehicle_details. vehicle_details_id
                                                                    INNER JOIN own ON own.vehicle_id = vehicle.vehicle_id
                                                                    INNER JOIN user ON own.user_id = user.user_id
                                                                    WHERE booking.booking_id = ?");

        if ($stmt) {
            $stmt->bind_param("i", $bookingId);
            $stmt->execute(); 
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $this->disconnect();
            return array("success" => TRUE, "data" => $result);
        } else {
            $this->disconnect();
            return array("success" => FALSE);
        }
    }

    public function getHistory ($vehicleId)
    {
    $this->connect("localhost", "root", "", "db_garage");

    $stmt = $this->connection->prepare("SELECT * FROM vehicle   INNER JOIN have ON vehicle.vehicle_id = have.vehicle_id
                                                                INNER JOIN booking ON booking.booking_id = have.booking_id
                                                                WHERE vehicle.vehicle_id = ?");

    if ($stmt) {
        $stmt->bind_param("i", $vehicleId);
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