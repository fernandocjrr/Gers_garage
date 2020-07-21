<?php

Class Staff
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

    public function getStaffs(){

        $this->connect("localhost", "root", "", "db_garage");

        $stmt = $this->connection->prepare("SELECT * FROM staff");                

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
}
?>