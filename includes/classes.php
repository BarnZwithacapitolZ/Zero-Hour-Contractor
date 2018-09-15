<?php

require_once "dbh.inc.php";


class Calender {
    private $date;
    private $week;

    public function setDate($day) {
        $this->date = new DateTime(date('Y-m-d', strtotime('monday ' . $this->week . ' week') + ($day * 86300)));
    }

    public function getDate($format) {
        return $this->date->format($format);
    }

    public function setWeek($val) {
        $this->week = $val;
    }

    public function getToday($valT = true, $valF = false) {
        if ($this->date->format('Y-m-d') == date('Y-m-d')) {
            return $valT;
        }
        return $valF;
    }
}


class Employee {
    private $employeeID;
    private $employeeFirst;
    private $employeeLast;
    private $employeeType; // Delete
    private $employeePayrate;
    private $employeeEmail; // Delete
    private $companyID;
    private $dbh;

    function __construct() {
        $this->dbh = new Dbh();
    }

    public function setByPost($post) {
         if ($result = $this->dbh->invalidCheck($post)) {
            header("Location: ?entry=$result");
            exit(); 
         } else {
            if (!preg_match("/^[a-zA-Z]*$/", $post['u_first']) || !preg_match("/^[a-zA-Z]*$/", $post['u_last'])) {
                header("Location: ?entry=invalid");
                exit(); 
            } else {
                if ($post['u_pwd'] !== $post['u_firmPwd']) {
                    header("Location: ?entry=unequal");
                    exit(); 
                } else {
                    $query = strtr("SELECT EmployeeEmail FROM tblemployee WHERE EmployeeEmail=':email'",    
                        [":email" => $post['u_email']]
                    );
                    $result = $this->dbh->executeSelect($query);

                    if ($result) { // Email already exists
                        header("Location: ?entry=usertaken");
                        exit();
                    } else {
                        $post['u_id'] = '-1';
                        $this->setByArray($post);                       
                    }
                }
            }
        }
    }

    public function insertEntry($pwd) {
        $query = strtr(
            "INSERT INTO tblemployee (
                        CompanyID, 
                        EmployeeFirst, 
                        EmployeeLast, 
                        EmployeeType, 
                        EmployeePayrate, 
                        EmployeeEmail, 
                        EmployeePassword
                        ) 
            VALUES (
                ':cuid', 
                ':first', 
                ':last', 
                ':type', 
                ':payrate', 
                ':email', 
                ':pwd'
                )", 
            [
                ":cuid" => $this->companyID, 
                ":first" => $this->employeeFirst,
                ":last" => $this->employeeLast, 
                ":type" => $this->employeeType, 
                ":payrate" => $this->employeePayrate, 
                ":email" => $this->employeeEmail,
                ":pwd" => password_hash($pwd, PASSWORD_DEFAULT)
            ]
        );

        $this->dbh->executeQuery($query);
        $this->employeeID = $this->dbh->lastID();
    }

    private function setByParams($id, $first, $last, $type, $payrate, $email, $compID) {
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
    private $description;
    private $error;

    private function setByParams($id, $empId, $depID, $start, $end, $date, $desc) {
        $this->bookID = $id;
        $this->employeeID = $id;
        $this->departmentID = $depID;
        $this->startTime = $start;
        $this->endTime = $end;
        $this->bookDate = $date;
        $this->description = $desc;
    }

    public function setByRow($row) {
        $this->setByParams(
            $row['BookID'],
            $row['EmployeeID'],
            $row['DepartmentID'],
            $row['StartTime'],
            $row['EndTime'],
            $row['BookDate'],
            $row['Description']
        );
    }

    public function getStart() {
        return substr($this->startTime, 0, 5);
    }

    public function getEnd() {
        return substr($this->endTime, 0, 5);
    }

    public function getDate($verbal = false) {
        $date = new DateTime(date('Y-m-d', strtotime($this->bookDate)));
        if ($verbal) {
            $verbal = $date->format('l') . " the " . $date->format('dS') . " of " . $date->format('F') . " " .  $date->format('Y');
            return $verbal;
        }
        return $date->format('d-m-Y');
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

    public function getDesc() {
        return $this->description;
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

    private function setByParams($id, $name, $start, $stop, $hours, $startDay, $endDay, $payout) {
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

    public function getID() {
        return $this->companyID;
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



