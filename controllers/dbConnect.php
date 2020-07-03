<?php

class dbConnect{
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $dbName = 'db_garage';
    public $connection;

    public function connect()                      //connect DB function
    {
        try {
            $this->connection = mysqli_connect($this->host, $this->user, $this->password, $this->dbName);      //connect
        } catch (Exception $e) {
            print_r($e->getMessage());                                             //if error, print message
            die();                                                                 // stop function
        }
    }

    public function disconnect()                                                  // disconect db function
    {
        mysqli_close($this->connection);
    }
}
?>