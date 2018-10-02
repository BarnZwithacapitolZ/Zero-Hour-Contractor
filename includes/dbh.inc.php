<?php

// Connect to the databse

function escape($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
} 

class Dbh{
    private $dbh;
    private $dbServername = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName = "zerocontractor";
    private $query;
    private $error = false;
    private $results;
    private $count = 0;

    public function __construct(){
        $this->dbh = new PDO("mysql:dbname=" . $this->dbName . ";host=" . $this->dbServername . ";",
        $this->dbUsername, $this->dbPassword);
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
   

    public function first() {
        return $this->results()[0];
    }

    public function query($stmt, $params = array()) {
        $this->error = false;
        if ($this->query = $this->dbh->prepare($stmt)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->query->bindValue($x, $param);
                    $x++;
                }
            }

            if ($this->query->execute()) {
                $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
                $this->count = $this->query->rowCount();
            } else {
                $this->error = $this->dbh->errorInfo();
            }
        }

        return $this;
    }

    public function multiAction($action, $table, $where = array()) {
        $condition = '';
        $values = array();

        foreach ($where as $key => $value) {
            $condition .= $value[0] . $value[1] . "?";
            $values[] = $value[2]; 

            if ($key !== count($where) - 1) {
                $condition .= " AND ";     
            }     
        }
        $stmt = "{$action} FROM {$table} WHERE {$condition}";
        if (!$this->query($stmt, $values)->error()) {
            return $this;
        }
        return false;
    }

    public function action($action, $table, $where = array()) {
        $field = $where[0];
        $operator = $where[1];
        $value = $where[2];

        $stmt = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
        if (!$this->query($stmt, array($value))->error()) {
            return $this;               
        }
        return false;
    }

    public function get($select, $table, $where = array()) {
        $fields = $select;
        if (is_array($select)) {
            $fields = implode(', ', $select);
        }

        if (is_array($where[0])) {
            return $this->multiAction("SELECT {$fields}", $table, $where);  
        } else {
            return $this->action("SELECT {$fields}", $table, $where); 
        }
        
        return false;
    }

    public function delete($table, $where = array()) {
        return $this->action('DELETE', $table, $where);
    }

    public function insert($table, $fields = array()) {
        if (count($fields)) {
            $keys = array_keys($fields);
            $values = null;

            $stmt = "INSERT INTO {$table} (" . implode(', ', $keys).") VALUES (" . implode(', ', array_fill(0, count($fields), '?')).")";

            if (!$this->query($stmt, $fields)->error()) {
                return true;
            }
        }
        return false; // No data
    }

    public function update($table, $id, $fields = array()) {
        $set = '';
        $x = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";
            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }       

        $stmt = "UPDATE {$table} SET {$set} WHERE EmployeeID={$id}"; 

        if (!$this->query($stmt, $fields)->error()) {
            return true;
        }

        return false;
    }

    public function results() {
        return $this->results;
    }

    public function error() {
        return $this->error;
    }

    public function count() {
        return $this->count;
    }
}