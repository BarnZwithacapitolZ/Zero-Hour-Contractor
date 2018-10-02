<?php
    session_start();
    $title = "Weekly Overview";
    $stylesheet = "main";

    date_default_timezone_set('Europe/London');
    require_once "../includes/header.inc.php";
    require_once "../includes/dbh.inc.php";
    require_once "../includes/classes.php";

    if (Session::exists('user')) {
        $user = new Employee();
        $user->setByID(Session::get('user'));

        $company = new Company();
        $company->setByID($user->getCUID());

        if (Input::exists()) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
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
                $entry = new Employee();

                try {
                    $entry->create(array(
                        'CompanyID' => $company->getID(),
                        'EmployeeFirst' => $_POST['first'],
                        'EmployeeLast' => $_POST['last'],
                        'EmployeeType' => 'employee',
                        'EmployeePayrate' => $_POST['payrate'],
                        'EmployeeEmail' => $_POST['email'],
                        'EmployeePassword' => password_hash($_POST['firmPwd'], PASSWORD_DEFAULT)
                    ));
                } catch(Exception $e) {
                    die($e->getMessage());
                }    
            } else {
                print_r($validation->getErrors()); 
            }         
        }            
    } else {
        header("Location: /zero-hour-contractor/index?login=nologin");
        exit();
    }
?>

        <div id="overview-manager" class="overview-manager__container">
            <form action="" method="POST" autocomplete="off">
                <input type="text" name="first"  placeholder="First Name" value="<?php echo escape(Input::get('first')); ?>" />
                <input type="text" name="last"  placeholder="Last Name" value="<?php echo escape(Input::get('last')); ?>" />
                <input type="text" name="email"  placeholder="Email Address" value="<?php echo escape(Input::get('email')); ?>" />
                <input type="text" name="payrate"  placeholder="Your payrate" value="<?php echo escape(Input::get('payrate')); ?>" />
                <input type="password" name="pwd"  placeholder="Password" />
                <input type="password" name="firmPwd"  placeholder="Confirm Password" />

                <input type="hidden" name="entry" />

                <button name="submit">Start</button>
            </form>
        </div>

        <?php require_once "../includes/side-nav.inc.php" ?>


<?php 
    require_once "../includes/footer.inc.php";
?>