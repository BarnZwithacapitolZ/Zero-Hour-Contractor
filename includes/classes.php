<?php

require_once "dbh.inc.php";

class Employee{
    private $employeeID;
    private $employeeName;
    private $employeeType; // Delete
    private $employeePayrate;
    private $employeeEmail; // Delete
    private $employeePassword; // Delete
    private $organizationID;
    private $error;

    public function setByParams($id, $name, $type, $payrate, $email, $password, $orgID){
        $this->employeeID = $id;
        $this->employeeName = $name;
        $this->employeeType = $type;
        $this->employeePayrate = $payrate;
        $this->employeeEmail = $email;
        $this->employeePassword = $password;
        $this->organizationID = $orgID;
    }

    public function setByRow($row){
        $this->setByParams(
            $row['EmployeeID'],
            $row['EmployeeName'],
            $row['EmployeeType'],
            $row['EmployeePayrate'],
            $row['EmployeeEmail'],
            $row['EmployeePassword'],
            $row['OrganizationID']
        );
    }

    public function getName(){
        return $this->employeeName;
    }

    public function getID(){
        return $this->employeeID;
    }
}

class HourTile{
    private $bookID;
    private $employeeID;
    private $departmentID;
    private $startTime;
    private $endTime;
    private $bookDate;
    private $error;

    public function setByParams($id, $empId, $depID, $start, $end, $date){
        $this->bookID = $id;
        $this->employeeID = $id;
        $this->departmentID = $depID;
        $this->startTime = $start;
        $this->endTime = $end;
        $this->bookDate = $date;
    }

    public function setByRow($row){
        $this->setByParams(
            $row['BookID'],
            $row['EmployeeID'],
            $row['DepartmentID'],
            $row['StartTime'],
            $row['EndTime'],
            $row['BookDate']
        );
    }

    public function getStart(){
        return substr($this->startTime, 0, 5);
    }

    public function getEnd(){
        return substr($this->endTime, 0, 5);
    }

    public function getDate(){
        return $this->bookDate;
    }

    public function getHours(){
        $time1 = new DateTime($this->startTime);
        $time2 = new DateTime($this->endTime);
        $interval = $time1->diff($time2);
        $elapsed = '';
        if ($interval->format('%h') > 0){
            $elapsed .= $interval->format('%h hour '); // Add hours if any
        } 
        if ($interval->format('%i') > 0) {
            $elapsed .= $interval->format('%i minute'); // Add minutes if any
        }
          
        return $elapsed;
    }

    public function getDepartment(){
        //make a connection with the department class to return the department name
        $dep = $this->departmentID;

        $query = "SELECT DepartmentName FROM tbldepartment WHERE DepartmentID='$dep'";
        $dbh = new Dbh();
        $name = $dbh->executeSelect($query);

        return $name[0]['DepartmentName'];
    }
}


class Department{
    private $departmentID;
    private $departmentName;
    private $departmentMinEmployees;

    public function setByParams($id, $name, $minEmp){
        $this->departmentID = $id;
        $this->departmentName = $name;
        $this->departmentMinEmployees = $minEmp;
    }

    public function setByRow($row){
        $this->setByParams(
            $row['DepartmentID'],
            $row['DepartmentName'],
            $row['DepartmentMinEmployees']
        );
    }
}



