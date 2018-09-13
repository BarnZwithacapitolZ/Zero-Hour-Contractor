<?php

require_once "dbh.inc.php";

class Employee {
    private $employeeID;
    private $employeeFirst;
    private $employeeLast;
    private $employeeType; // Delete
    private $employeePayrate;
    private $employeeEmail; // Delete
    private $companyID;
    private $error;

    public function setByParams($id, $first, $last, $type, $payrate, $email, $compID) {
        $this->employeeID = $id;
        $this->employeeFirst = $first;
        $this->employeeLast = $last;
        $this->employeeType = $type;
        $this->employeePayrate = $payrate;
        $this->employeeEmail = $email;
        $this->companyID = $compID;
    }

    public function setByArray($array){
        extract($array);     
        $this->setByParams(
            $u_id,
            $u_first,
            $u_last,
            $u_type,
            $u_payrate,
            $u_email,
            $u_cuid
        );
    }

    public function setByRow($row) {
        $this->setByParams(
            $row['EmployeeID'],
            $row['EmployeeFirst'],
            $row['EmployeeLast'],
            $row['EmployeeType'],
            $row['EmployeePayrate'],
            $row['EmployeeEmail'],
            $row['CompanyID']
        );
    }

    public function getName($length = "first") {
        if ($length == "full") {
            return $this->employeeFirst . " " . $this->employeeLast;
        } else {
            return $this->employeeFirst;
        }
    }

    public function getID() {
        return $this->employeeID;
    }

    public function getCUID() {
        return $this->companyID;
    }

    public function getType() {
        return $this->employeeType;
    }
}

class HourTile {
    private $bookID;
    private $employeeID;
    private $departmentID;
    private $startTime;
    private $endTime;
    private $bookDate;
    private $error;

    public function setByParams($id, $empId, $depID, $start, $end, $date) {
        $this->bookID = $id;
        $this->employeeID = $id;
        $this->departmentID = $depID;
        $this->startTime = $start;
        $this->endTime = $end;
        $this->bookDate = $date;
    }

    public function setByRow($row) {
        $this->setByParams(
            $row['BookID'],
            $row['EmployeeID'],
            $row['DepartmentID'],
            $row['StartTime'],
            $row['EndTime'],
            $row['BookDate']
        );
    }

    public function getStart() {
        return substr($this->startTime, 0, 5);
    }

    public function getEnd() {
        return substr($this->endTime, 0, 5);
    }

    public function getDate() {
        return $this->bookDate;
    }

    public function getHours() {
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

    public function getDepartment() {
        //make a connection with the department class to return the department name
        $dep = $this->departmentID;

        $query = "SELECT DepartmentName FROM tbldepartment WHERE DepartmentID='$dep'";
        $dbh = new Dbh();
        $name = $dbh->executeSelect($query);

        return $name[0]['DepartmentName'];
    }
}


class Company {
    private $companyID;
    private $companyName;
    private $companyStart;
    private $companyStop;
    private $companyMaxHours;
    private $companyStartDay;
    private $companyEndDay;
    private $companyPayout;

    public function setByParams($id, $name, $start, $stop, $hours, $startDay, $endDay, $payout) {
        $this->companyID = $id;
        $this->companyName = $name;
        $this->companyStart = $start;
        $this->companyStop = $stop;
        $this->companyMaxHours = $hours;
        $this->companyStartDay = $startDay;
        $this->companyEndDay = $endDay;
        $this->companyPayout = $payout;
    }

    public function setByArray($array) {
        extract($array);
        $this->setByParams(
            $c_id,
            $c_name,
            $c_start,
            $c_stop,
            $c_hours,
            $c_startDay,
            $c_endDay,
            $c_payout
        );
    }

    public function getDays() {
        $numDays = ($this->companyEndDay - $this->companyStartDay) + 1;
        return "day" . $numDays;
    }

    public function getStart() {
        return $this->companyStartDay;
    }

    public function getEnd() {
        return $this->companyEndDay;
    }
}



class Department {
    private $departmentID;
    private $departmentName;
    private $departmentMinEmployees;

    public function setByParams($id, $name, $minEmp) {
        $this->departmentID = $id;
        $this->departmentName = $name;
        $this->departmentMinEmployees = $minEmp;
    }

    public function setByRow($row) {
        $this->setByParams(
            $row['DepartmentID'],
            $row['DepartmentName'],
            $row['DepartmentMinEmployees']
        );
    }
}



