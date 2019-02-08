<?php

require_once "dbh.inc.php";

function sorted($a,$b)
{
    if ($a[0] == $b[0]) return 0;
    return ($a[0] < $b[0]) ? -1 : 1;
}

class Calender {
    private $date;
    private $week;

    public function setDate($day) {
        $this->date = new DateTime(date('Y-m-d', strtotime('monday ' . $this->week . ' week') + ($day * 86300)));
    }

    public function getDate($format = 'Y-m-d') {
        return $this->date->format($format);
    }

    public function getDateVerbal() {
        return $this->date->format('l') . " the " . $this->date->format('dS') . " of " . $this->date->format('F') . " " .  $this->date->format('Y');
    }

    public function setWeek($val) {
        $this->week = $val;
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

    public function update($fields = array()) {
        if (!$this->tbh->update('tblbook', $this->bookID, $fields)) {
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


class Cover {
    public $day;
    public $department;
    public $time = array();
    public $id;

    // public function __construct($day, $dep, $time = array(), $id) {
    //     $this->day = $day;
    //     $this->department = $dep;
    //     $this->time = $time;
    //     $this->id = $id;
    // }

    public function getGaps($shifts, $start, $end) {
        if (count($shifts) < 1) {
            return;
        }   
        $last = $start;
        $lastShift = array($shifts[0][0], $shifts[0][1]);
        $cases = array();
        
        foreach ($shifts as $shift) {
            if ($shift[0] > $lastShift[0] && $shift[1] < $lastShift[1]) {
                continue;
            }
            $diff = $last->diff($shift[0])->format('%R%h:%i');

            if ($diff > 0) {
                $cases[] = array($last->format('h:i'), $shift[0]->format('h:i'));
            }
            $last = $shift[1];
            $lastShift = array($shift[0], $shift[1]);
        }

        $diff = $last->diff($end)->format('%R%h:%i');
        if ($diff > 0) {
            $cases[] = array($last->format('h:i'), $end->format('h:i'));
        }
        return $cases;
    }
    
    public function getCover($data, $company) {
        $cStart = new DateTime($company->CompanyStart);
        $cEnd = new DateTime($company->CompanyStop);

        foreach($data as $day => $shift) { // Dont need
            foreach ($shift as $key => $dep) { // Dont need (loop through in overview, when displaying the data)
                $full = array();
                foreach ($dep as $time) {
                    $start = new DateTime($time[0]);
                    $end = new DateTime($time[1]);
                    $full[] = array($start, $end);
                }
                usort($full, "sorted");
                $cases = $this->getGaps($full, $cStart, $cEnd);
                if (count($cases) > 0) {
                    return $cases;
                }
            }
        }
        return array($cStart, $cEnd);
    }

}

