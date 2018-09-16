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
    private $data;
    private $dbh;

    function __construct() {
        $this->dbh = new Dbh();
    }

    public function create($fields = array()) {
        if (!$this->dbh->insert('tblemployee', $fields)) {
            throw new Exception("There was a problem creating your user account.");
        }     
    }

    public function find($email = null, $cuid = null) {
        if ($email && $cuid) {
            $data = $this->dbh->get('*', 'tblemployee', array(
                array('EmployeeEmail', '=', $email),
                array('CompanyID', '=', $cuid)
            ));

            if ($data->count()) {
                $this->data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function login($email = null, $pwd = null, $cuid = null) {
        $user = $this->find($email, $cuid);

        if ($user) {
            if (password_verify($pwd, $this->data()->EmployeePassword)) {
                return true; // Successful login
            }
        }
        return false;
    }

    private function data() {
        return $this->data;
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

class Company {
    private $companyID;
    private $companyName;
    private $companyStart;
    private $companyStop;
    private $companyMaxHours;
    private $companyStartDay;
    private $companyEndDay;
    private $companyPayout;
    private $dbh;

    function __construct() {
        $this->dbh = new Dbh();
    }

    public function create($fields = array()) {
        if (!$this->dbh->insert('tblcompany', $fields)) {
            throw new Exception("There was a problem creating your company account.");
        }     
    }

    public function getLast() {
        return $this->dbh->lastID();
    }  

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


Class Validate {
    private $passed = false;
    private $errors = array();
    private $dbh;

    function __construct() {
        $this->dbh = new Dbh();
    }

    public function check($source, $items = array()) {
        foreach($items as $item => $rules) {
            foreach($rules as $rule => $ruleValue) {
                $value = $source[$item];

                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                } else if(!empty($value)) {
                    switch($rule) {
                        case 'min':
                            if (strlen($value) < $ruleValue) {
                                $this->addError("{$item} must be a minimum of {$ruleValue} characters");
                            }
                        break;
                        case 'max':
                            if (strlen($value) > $ruleValue) {
                                $this->addError("{$item} must be a maximum of {$ruleValue} characters");
                            }
                        break;
                        case 'time':
                            if (DateTime::createFromFormat('G:i', $value) === false) {
                                $this->addError("{$item} must be a valid time");
                            }
                        break;
                        case 'int':
                            if (!filter_var($value, FILTER_VALIDATE_INT)) {
                                $this->addError("{$item} must be a valid number");
                            }
                        break;
                        case 'float':
                            if (!filter_var($value, FILTER_VALIDATE_FLOAT)) {
                                $this->addError("{$item} must be a valid number");
                            }
                        break;
                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError("{$item} must be valid");
                            }
                        break;
                        case 'string':
                            if (!preg_match("/^[a-zA-Z]*$/", $value)) {
                                $this->addError("{$item} must not contain any spaces or letters");
                            }
                        break;
                        case 'matches':
                            if ($value != $source[$ruleValue]) {
                                $this->addError("{$ruleValue} must match {$item}");
                            }
                        break;
                        case 'unique':
                            $result = $this->dbh->get('EmployeeEmail', $ruleValue[0], array($ruleValue[1], '=', $value));
                            if ($result->count()) {
                                $this->addError("{$item} already exists");
                            }
                        break;
                    }
                }
            }
        }
        if (empty($this->errors)) {
            $this->passed = true;
        }

        return $this;
    }

    private function addError($error) {
        $this->errors[] = $error;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function passed() {
        return $this->passed;
    }
}

