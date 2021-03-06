<?php

require_once "dbh.inc.php";

function sorted($a,$b)
{
    if ($a[0] == $b[0]) return 0;
    return ($a[0] < $b[0]) ? -1 : 1;
}

class Calender {
    private $date;
    private $day;
    private $month;
    private $year;

    public function setDate($i) {
        $this->date = new DateTime(date("Y-m-d", strtotime($this->year . 'W' . date('W', 
            strtotime($this->year . '-' . $this->month . '-' . $this->day)) . '1') + ($i * 86300)));
    }

    public function getDate($format = 'Y-m-d') {
        return $this->date->format($format);
    }

    public function getDateVerbal() {
        return $this->date->format('l') . " the " . $this->date->format('dS') . " of " . $this->date->format('F') . " " .  $this->date->format('Y');
    }

    public function incrementWeek($format) {
        $date = strtotime($this->year . '-' . $this->month . '-' . $this->day);
        return date($format, strtotime('+1 weeks', $date));
    }

    public function decrementWeek($format) {
        $date = strtotime($this->year . '-' . $this->month . '-' . $this->day);
        return date($format, strtotime('-1 weeks', $date));
    }

    public function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function setDates($date) {      
        $this->day = date('d', strtotime($date));
        $this->month = date('m', strtotime($date));
        $this->year = date('Y', strtotime($date));
    }

    public function setDay($day) {
        $this->day = $day;
    }

    public function setMonth($month) {  
        $this->month = $month;
    }

    public function setYear($year) {
        $this->year = $year;
    }

    public function checkToday($valT = true, $valF = false) {
        if ($this->date->format('Y-m-d') == date('Y-m-d')) {
            return $valT;
        }
        return $valF;
    }

    public function getToday($format = 'Y-m-d') {
        return date($format);
    }
}

class Employee {
    private $dbh;

    function __construct() {
        $this->dbh = new Dbh();
    }

    public function getLast() {
        return $this->dbh->lastID();
    }  

    public function getFromCuid($cuid){
        $result = $this->dbh->get('*', 'tblemployee', array("CompanyID", '=', $cuid));
        if ($result->count()) {
            return $result->results();
        }
        return false;
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
                return $data->first();
            }
        }
        return false;
    }

    public function login($email = null, $pwd = null, $cuid = null) {
        $user = $this->find($email, $cuid);

        if ($result = $user) {
            if (password_verify($pwd, $result->EmployeePassword)) { // only if password is correct
                Session::put('user', $result->EmployeeID);
                return true; // Successful login
            }
        }
        return false;
    }

    public function getByID($id) {
        $result = $this->dbh->get('*', 'tblemployee', array('EmployeeID', '=', $id));
        if ($result->count()) {
            return $result->first();
        }
        return false;
    }

    public function getFullName($data) {
        return $data->EmployeeFirst . " " . $data->EmployeeLast;
    }
}

class Company {
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

    public function getByID($id) {
        $result = $this->dbh->get('*', 'tblcompany', array('CompanyID', '=', $id));
        if ($result->count()) {
            return $result->first();
        }
        return false;
    }

    public function getStart($data) {
        return substr($data->CompanyStart, 0, -3);
    }

    public function getStop($data) {
        return substr($data->CompanyStop, 0, -3);
    }
}

class HourTile {
    private $error;
    private $dbh;

    function __construct() {
        $this->dbh = new Dbh();
    }

    public function getByIdDate($id, $date) {
        $result = $this->dbh->get('*', 'tblbook', array(
            array('EmployeeID', '=', $id),
            array('BookDate', '=', $date)
        ), array(
            'StartTime',
            'EndTime'
        ));
        if ($result->count()) {
            return $result->results();
        }
        return false;
    }

    public function create($fields = array()) {
        if (!$this->dbh->insert('tblbook', $fields)) {
            throw new Exception("There was a problem processing your request."); // When false returned
        }
    }

    public function update($id, $fields = array()) {
        if (!$this->dbh->update('tblbook', $fields, array('BookID', '=', $id))) {
            throw new Exception("There was a problem processing your request."); // When false returned
        }
    }

    public function delete($id) {
        if (!$this->dbh->delete('tblbook', array('BookID', '=', $id))) {
            throw new Exception("There was a problem processing your request."); // When false returned
        }
    }

    public function getStart($data) {
        return substr($data->StartTime, 0, 5);
    }

    public function getEnd($data) {
        return substr($data->EndTime, 0, 5);
    }

    public function getHours($data) {
        $time1 = new DateTime($data->StartTime);
        $time2 = new DateTime($data->EndTime);
        $interval = $time1->diff($time2);
        $elapsed = '';
        if ($interval->format('%h') > 0) {
            $elapsed .= $interval->format('%h hour '); // Add hours if any
        } 
        if ($interval->format('%i') > 0) {
            $elapsed .= $interval->format('%i minute'); // Add minutes if any
        }
          
        return $elapsed;
    }

    public function getRunning($data) {
        $time1 = new DateTime($data->StartTime);
        $time2 = new DateTime($data->EndTime);
        $interval = $time1->diff($time2);
        $elapsed = '';
        if ($interval->format('%h') > 0) {
            $elapsed .= $interval->format('%h.'); // Add hours if any
        } 
        if ($interval->format('%i') > 0) {
            $elapsed .= $interval->format('%i'); // Add minutes if any
        }
          
        return $elapsed;
    }

    public function getDepartment($id, $departments) {
        foreach ($departments as $dep) {
            if ($dep->DepartmentID == $id) {
                return $dep->DepartmentName;
            }
        }
        return $id;
    }

    public function getMinEmployee($id, $departments) {
        foreach ($departments as $dep) {
            if ($dep->DepartmentID == $id) {
                return $dep->DepartmentMinEmployees;
            }
        }
        return $id;
    }
}

class Department {
    private $dbh;

    function __construct() {
        $this->dbh = new Dbh();
    }

    public function create($fields = array()) {
        if (!$this->dbh->insert('tbldepartment', $fields)) {
            throw new Exception("There was a problem creating your user account.");
        } 
    }

    public function getDepByComp($cuid) {
        $result = $this->dbh->get('*', 'tbldepartment', array('CompanyID', '=', $cuid));
        if ($result->count()) {
            return $result->results();
        }
        return false;
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
                        case 'date':
                            if (DateTime::createFromFormat('Y-m-d', $value) === false) {
                                $this->addError("{$item} must be a valid date");
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

class Input {
    public static function exists($name, $type = 'post') {
        switch($type) {
            case 'post':
                return (isset($_POST[$name])) ? true : false;
            break;  
            case 'get':
                return (isset($_GET[$name])) ? true : false;
            break; 
            default:
                return false;
            break;
        }
    }

    public static function get($item, $default = '') {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } else if (isset($_GET[$item])) {
            return $_GET[$item];
        }
        return $default;
    }
}

class Session {
    public static function exists($name) {
        return (isset($_SESSION[$name])) ? true : false;
    }

    public static function put($name, $value) {
        return $_SESSION[$name] = $value;
    }

    public static function get($name) {
        return $_SESSION[$name];
    }

    public static function delete($name) {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }
}

class Shift {
    private $day;
    private $startTime;
    private $endTime;
    private $department;

    function __construct($day, $startTime, $endTime, $department) {
        $this->day = $day;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->department = $department;
    }

    public function getDay() {
        return $this->day;
    }

    public function getStartTime() {
        return $this->startTime->format('H:i');
    }

    public function getEndTime() {
        return $this->endTime->format('H:i');
    }

    public function getDepartment() {
        return $this->department;
    }
}

class Cover {
    private $shifts = array(); // array of shift objects

    public function addShift($day, $startTime, $endTime, $department) {
        $start = new DateTime($startTime);
        $end = new DateTime($endTime);
        $this->shifts[$day][$department][] = array($start, $end);
    }

    private static function sortArrayTimes($a,$b) {
        if ($a == $b) return 0;
        return ($a < $b) ? -1 : 1;
    }

    public function getShifts($cStart, $cEnd) {
        if (count($this->shifts) < 1) {
            return;
        }

        $cases = array();
        foreach($this->shifts as $day => $days) { // for each day
            foreach($days as $dep => $deps) { // for each department
                usort($deps, array($this, 'sortArrayTimes'));

                $last = $cStart;
                $lastShift = array($deps[0][0], $deps[0][1]);

                foreach ($deps as $shift) { // for each shift on that day in that department
                    if ($shift[0] > $lastShift[0] && $shift[1] < $lastShift[1]) {      
                        continue;
                    }

                    if ($shift[0] > $last) { 
                        $cases[] = new Shift($day, $last, $shift[0], $dep);
                    }
                    $last = $shift[1];
                    $lastShift = array($shift[0], $shift[1]);
                }

                if ($cEnd > $last) {
                    $cases[] = new Shift($day, $last, $cEnd, $dep);
                }
            }
        }
        return $cases;
    }
}
