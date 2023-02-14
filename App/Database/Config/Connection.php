<?php 

namespace App\Database\Config;

class Connection {
    Private $hostname = "localhost";
    Private $database = "system";
    Private $hostUsername = "root";
    Private $hostPassword = "";
    Private $Port = 3306;

    public \mysqli $connection;

    // open connection to database
    public function __construct()
    {
        $this->connection = new \mysqli($this->hostname , $this->hostUsername , $this->hostPassword , $this->database , $this->Port);

        // check connection
        // if($this->connection->connect_error){
        //     die("connection failed : " . $this->connection->connect_error);
        // }

        // echo "connected successfully";
    }

    // close connection to database
    public function __destruct()
    {
        $this->connection->close();
    }
}


?>