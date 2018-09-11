<?php

// Connect to the databse

class Dbh{
    private $dbh;
    private $dbServername = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName = "zerocontractor";
    private $error;

    public function __construct(){
        $this->dbh = new PDO("mysql:dbname=" . $this->dbName . ";host=" . $this->dbServername . ";",
        $this->dbUsername, $this->dbPassword);
    }

    public function invalidCheck($array) {
        foreach ($array as $key => $var) {
            if (!$var) {
                return $key;
            }
        }
        return null;
    }

    public function lastID() {
        return $this->dbh->lastInsertId();
    }

    public function executeQuery($query) {
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $this->dbh->prepare($query);
        $result = $stmt->execute();
        $this->error = $this->dbh->errorInfo();
    }

    public function executeSelect($query) {
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $this->dbh->prepare($query);
        $result = $stmt->execute();

        $this->error = $this->dbh->errorInfo();
        //print_r($this->error);

        $entry = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //print_r($entry);

        return $entry;
    }
}