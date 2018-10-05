<?php
    session_start();

    $title = "Register";
    $stylesheet = "landing";

    date_default_timezone_set('Europe/London');
    require_once "includes/header.inc.php";
    require_once "includes/dbh.inc.php";
    require_once "includes/classes.php";

    if (Input::exists()) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 40
            ),
            'start' => array(
                'required' => true,
                'time' => true
            ),
            'stop' => array(
                'required' => true,
                'time' => true
            ),
            'hours' => array(
                'required' => true,
                'int' => true
            ),
            'startDay' => array(
                'required' => true,
                'int' => true
            ),
            'endDay' => array(
                'required' => true,
                'int' => true
            ),
            'payout' => array(
                'required' => true,
                'string' => true
            ),
            'first' => array(
                'required' => true,
                'string' => true,
                'min' => 2,
                'max' => 20
            ),
            'last' => array(
                'required' => true,
                'string' => true,
                'min' => 2,
                'max' => 20
            ),
            'email' => array(
                'required' => true,
                'email' => true,
                'unique' => array('tblemployee', 'EmployeeEmail')
            ),
            'payrate' => array(
                'required' => true,
                'float' => true
            ),
            'pwd' => array(
                'required' => true,
                'min' => 6
            ),
            'firmPwd' => array(
                'required' => true,
                'matches' => 'pwd',
                'min' => 6
            )    
        ));

        if ($validation->passed()) {
            $user = new Employee();
            $company = new Company();
            try {
                $company->create(array(
                    'CompanyName' => $_POST['name'],
                    'CompanyStart' => $_POST['start'],
                    'CompanyStop' => $_POST['stop'],
                    'CompanyMaxHours' => $_POST['hours'],
                    'CompanyStartDay' => $_POST['startDay'],
                    'CompanyEndDay' => $_POST['endDay'],
                    'CompanyPayout' => $_POST['payout']
                ));             
                try {
                    $user->create(array(
                        'CompanyID' => $company->getLast(),
                        'EmployeeFirst' => $_POST['first'],
                        'EmployeeLast' => $_POST['last'],
                        'EmployeeType' => 'admin',
                        'EmployeePayrate' => $_POST['payrate'],
                        'EmployeeEmail' => $_POST['email'],
                        'EmployeePassword' => password_hash($_POST['firmPwd'], PASSWORD_DEFAULT)
                    ));

                    Session::put('user', $user->getLast());
                    //header("Location: app/overview?registration=success");

                } catch(Exception $e) {
                    die($e->getMessage());
                }    
            } catch(Exception $e) { 
                die($e->getMessage()); // redirect saying they couldnt log in for whatever reason
            }
        } else {
            foreach($validation->getErrors() as $error) {
                echo $error, '<br>';
            }
        }  
    }
?>

    <form action="" method="POST" autocomplete="off">
        <input type="text" name="name" placeholder="Company Name" value="<?php echo escape(Input::get('name')); ?>" />
        <input type="time" name="start" value="<?php echo escape(Input::get('start', '08:00')); ?>" />
        <input type="time" name="stop" value="<?php echo escape(Input::get('stop', '22:00')); ?>" />
        <input type="text" name="hours" placeholder="Max Hours" value="<?php echo escape(Input::get('hours')); ?>" />
        <!--<input type="text" name="days"  placeholder="Days Open" /> -->

        <select name="startDay" placeholder="Weekly Start" >
            <option value="1">Monday</option>
            <option value="2">Tuesday</option>
            <option value="3">Wednesday</option>
            <option value="4">Thursday</option>
            <option value="5">Friday</option>
            <option value="6">Saturday</option>
            <option value="7">Sunday</option>
        </select> 

        <select name="endDay" placeholder="Weekly Stop">
            <option value="1">Monday</option>
            <option value="2">Tuesday</option>
            <option value="3">Wednesday</option>
            <option value="4">Thursday</option>
            <option value="5" selected="selected">Friday</option>
            <option value="6">Saturday</option>
            <option value="7">Sunday</option>
        </select> 

        <select name="payout"  placeholder="Payout">
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>

        <!-- maybe add a color or something here ????? -->

        <br /> <br />

        <input type="text" name="first" placeholder="First Name" value="<?php echo escape(Input::get('first')); ?>" />
        <input type="text" name="last" placeholder="Last Name" value="<?php echo escape(Input::get('last')); ?>" />
        <input type="text" name="email" placeholder="Email Address" value="<?php echo escape(Input::get('email')); ?>" />
        <input type="text" name="payrate" placeholder="Your payrate" value="<?php echo escape(Input::get('payrate')); ?>" />
        <input type="password" name="pwd" placeholder="Password" />
        <input type="password" name="firmPwd" placeholder="Confirm Password" />

        <input type="hidden" name="register" />

        <button name="submit">Start</button>
    </form>

<?php 
    require_once "includes/footer.inc.php";
?>