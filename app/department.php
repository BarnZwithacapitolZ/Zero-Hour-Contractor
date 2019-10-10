<?php
    session_start();
    $title = "Departments";
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
                'name' => array(
                    'required' => true,
                    'max' => 15
                ),
                'minEmployee' => array(
                    'required' => true,
                    'int' => true
                )
            ));

            if ($validation->passed()) {
                $entry = new Department();

                try {
                    $entry->create(array(
                        'CompanyID' => $company->CompanyID,
                        'DepartmentName' => $_POST['name'],
                        'DepartmentMinEmployees' => $_POST['minEmployee']
                    ));

                    $_POST = array();
                    header("Location: department?department=success");
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
            <div class="overview-manager__cell overview-manager__cell--header day2">
                <div class="cell__content"><div class="cell__text-content"><span>Department Name</span></div></div>
            </div>

            <div class="overview-manager__cell overview-manager__cell--header day2">
                <div class="cell__content"><div class="cell__text-content"><span>No. Min Employees</span></div></div>
            </div>

            <div class="overview-manager__cell overview-manager__cell--header day2">
                <div class="cell__content"><div class="cell__text-content"><span>Edit</span></div></div>
            </div>
        </div>
    </div>


    <form action="" method="POST" autocomplete="off">
        <input type="text" name="name"  placeholder="Department Name" value="<?php echo escape(Input::get('name')); ?>" />
        <input type="text" name="minEmployee"  placeholder="Min Number of Employees" value="<?php echo escape(Input::get('minEmployee')); ?>" />

        <input type="hidden" name="entry" />

        <button name="submit">Submit</button>
    </form>
</div>



<?php require_once "../includes/side-nav.inc.php"; ?>
<?php 
    require_once "../includes/footer.inc.php";
?>