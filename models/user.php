<?php

class User
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

    public function createUser($fname, $surname, $address, $phone, $sp_email, $sp_password) //sign in function
    {
        $this->connect("localhost", "root", "", "db_garage");                               //connect to db

        $stmt = $this->connection->prepare("INSERT INTO user (first_name, surname, phone, address, email, password)
        VALUES (?,?,?,?,?,?)");                 //????

        if ($stmt) {                                                                                    //if stmt succsessful
            $stmt->bind_param("ssssss", $fname, $surname, $phone, $address, $sp_email, $sp_password);   //replace ? for parameter
            $stmt->execute();                                                                           //execute query
            $this->disconnect();                                                                        //disconect from db
            return array("success" => TRUE);                                                            //return array ['success' = true]
        } else {
            $this->disconnect();                                                                        //if query dont work (what comes from dp?) disconnect
            return array("success" => FALSE);                                                           //return array ['success' = false
        }
    }

    public function checkUser($sp_email)                                                                //function to check if user is already in dp
    {
        $this->connect("localhost", "root", "", "db_garage");                                           //connect to db

        $stmt = $this->connection->prepare("SELECT user_id FROM user WHERE email = ? LIMIT 1");         //prepare query, if 1 email found stop query

        if ($stmt) {                                                                                    //if stmt true
            $stmt->bind_param("s", $sp_email);                                                          //replace ? for parameter
            $stmt->execute();                                                                           //execute query
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);                                     //store in $result the user found
            $this->disconnect();                                                                        //disconnect db
            return array("success" => TRUE, "data" => $result);                                         //return user found and success = true
        } else {
            $this->disconnect();                                                                        //disconnect db
            return array("success" => FALSE);                                                           //problem
        }
    }

    public function login($sp_email, $sp_password)
    {

        $this->connect("localhost", "root", "", "db_garage");

        $stmt = $this->connection->prepare("SELECT user_id FROM user WHERE email = ? AND password = ?");

        if ($stmt) {
            $stmt->bind_param("ss", $sp_email, $sp_password);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $this->disconnect();
            return array("success" => TRUE, "data" => $result);
        } else {
            $this->disconnect();
            return array("success" => FALSE);
        }
    }

    public function setUserSessionAndCookie($userID)                     //
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION["user_".strval($userID)] = TRUE;
        setcookie ("userID", $userID, 0, "/");
    }

    public function checkUserSession($userID)                     //
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return isset ($_SESSION["user_".strval($userID)]);
    }

    public function getUserCookie()                     //
    {
        if (isset($_COOKIE["userID"])){
            return $_COOKIE["userID"];
        } else {
            return null;
        }
    }

    public function isAdmin($userID)
    {
        $this->connect("localhost", "root", "", "db_garage");

        $stmt = $this->connection->prepare("SELECT admin FROM user WHERE user_id = ? LIMIT 1");

        if ($stmt) {
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $this->disconnect();
            return array("success" => TRUE, "data" => $result);
        } else {
            $this->disconnect();
            return array("success" => FALSE);
        }
    }

    public function getUserInfo($userId) //sign in function
    {
        $this->connect("localhost", "root", "", "db_garage");                               //connect to db

        $stmt = $this->connection->prepare("SELECT first_name, surname, phone, address, email FROM user WHERE user_id = ?");                 //????

        if ($stmt) {                                                                                    //if stmt succsessful
            $stmt->bind_param("i", $userId);   //replace ? for parameter
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);                                           //execute query
            $this->disconnect();                                                                        //disconect from d
            return array("success" => TRUE, "data" => $result);                                              //return array ['success' = true]
        } else {
            $this->disconnect();                                                                //if query dont work (what comes from dp?) disconnect
            return array("success" => FALSE);                                                           //return array ['success' = false
        }
    }

    public function logout($userID)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION["user_".strval($userID)]);
        setcookie ("userID", $userID, time()-3600, "/");
    }
}
