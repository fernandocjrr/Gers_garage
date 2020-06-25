<?php

class User
{
    private $connection;
    private $msgError = "";

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

    public function createUser($fname, $surname, $address, $phone, $sp_email, $sp_password)
    {
        $this->connect("localhost", "root", "", "db_garage");

        $stmt = $this->connection->prepare("INSERT INTO user (first_name, surname, phone, address, email, password)
        VALUES (?,?,?,?,?,?)");

        if ($stmt) {
            $stmt->bind_param("ssssss", $fname, $surname, $address, $phone, $sp_email, $sp_password);
            $stmt->execute();
            $this->disconnect();
            return array("success" => TRUE);
        } else {
            $this->disconnect();
            return array("success" => FALSE);
        }
    }

    public function checkUser($sp_email)
    {
        $this->connect("localhost", "root", "", "db_garage");

        $stmt = $this->connection->prepare("SELECT user_id FROM user WHERE email = ? LIMIT 1");

        if ($stmt) {
            $stmt->bind_param("s", $sp_email);
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
