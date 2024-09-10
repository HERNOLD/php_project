<?php
namespace App\Database;

class DB{

    const HOST='localhost';
    const USER='root';
    const PASSWORDű=null;
    const DATABASE='php_project';
    protected $mysqli;

    function __construct($host=self::HOST, $user=self::USER, $password=self::PASSWORDű, $database=self::DATABASE){
        $this->mysqli=mysqli_connect($host, $user, $password, $database);

        if(!$this->mysqli){
            die("Connection failed: ".mysqli_connect_error());
        }
        $this->mysqli->set_charset("utf8mb4");
    }

    function __destruct(){
        $this->mysqli->close();
    }
}