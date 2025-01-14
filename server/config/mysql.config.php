<?php

class systemConfig {
    var $dbConnect;

    public function __construct() {

    }

    public function __destruct() {
    }


    function connectDB() {
        $this->dbConnect = new mysqli('localhost', 'root', '', 'db_name');
        if ($this->dbConnect->connect_errno) {
            return null;
        } else {
            return $this->dbConnect;
        }
    }

    

}

?>