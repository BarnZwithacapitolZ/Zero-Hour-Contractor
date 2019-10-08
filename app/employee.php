<?php
    session_start();
    $title = "Employees";
    $stylesheet = "main";

    date_default_timezone_set('Europe/London');
    require_once "../includes/header.inc.php";
    require_once "../includes/dbh.inc.php";
    require_once "../includes/classes.php";

    if (Session::exists('user')) {
        $employee = new Employee();
        $user = $employee->getByID(Session::get('user'));

        $organization = new Company();
        $company = $organization->getByID($user->CompanyID);

        if (Input::exists('submit')) {
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
                        'CompanyID' => $company->CompanyID,
                        'EmployeeFirst' => $_POST['first'],
                        'EmployeeLast' => $_POST['last'],
                        'EmployeeType' => 'employee',
                        'EmployeePayrate' => $_POST['payrate'],
                        'EmployeeEmail' => $_POST['email'],
                        'EmployeePassword' => password_hash($_POST['firmPwd'], PASSWORD_DEFAULT)
                    ));

                    $_POST = array(); // Clear post to allow for new entries
                } catch(Exception $e) {
                    print_r($e->getMessage());
                }    
            } else {
                foreach($validation->getErrors() as $error) {
                    echo $error, '<br>';
                }
            }         
        }            
    } else {
        header("Location: /zero-hour-contractor/index?login=nologin");
        exit();
    }
?>

<div id="overview-manager" class="overview-manager__container">
    <header id="header__overview-header">

    </header>

    <div class="overview-manager__table">
        <div class="overview-manager__row overview-manager__row--header">
            <div class="overview-manager__cell overview-manager__cell--header day5">
                <div class="cell__content"><div class="cell__text-content"><span>Name</span></div></div>
            </div>

            <div class="overview-manager__cell overview-manager__cell--header day5">
                <div class="cell__content"><div class="cell__text-content"><span>Email</span></div></div>
            </div>

            <div class="overview-manager__cell overview-manager__cell--header day5">
                <div class="cell__content"><div class="cell__text-content"><span>Payrate</span></div></div>
            </div>

            <div class="overview-manager__cell overview-manager__cell--header day5">
                <div class="cell__content"><div class="cell__text-content"><span>Type</span></div></div>
            </div>

            <div class="overview-manager__cell overview-manager__cell--header day5">
                <div class="cell__content"><div class="cell__text-content"><span>No. Hours this Week</span></div></div>
            </div>

            <div class="overview-manager__cell overview-manager__cell--header day5">
                <div class="cell__content"><div class="cell__text-content"><span>Edit</span></div></div>
            </div>
        </div>
    </div>


    <form action="" method="POST" autocomplete="off">
        <input type="text" name="first"  placeholder="First Name" value="<?php echo escape(Input::get('first')); ?>" />
        <input type="text" name="last"  placeholder="Last Name" value="<?php echo escape(Input::get('last')); ?>" />
        <input type="text" name="email"  placeholder="Email Address" value="<?php echo escape(Input::get('email')); ?>" />
        <input type="text" name="payrate"  placeholder="Your payrate" value="<?php echo escape(Input::get('payrate')); ?>" />
        <input type="password" name="pwd"  placeholder="Password" />
        <input type="password" name="firmPwd"  placeholder="Confirm Password" />

        <input type="hidden" name="entry" />

        <button name="submit">Submit</button>
    </form>
</div>

<?php require_once "../includes/side-nav.inc.php" ?>

<?php 
    require_once "../includes/footer.inc.php";
?>