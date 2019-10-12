<?php
    session_start();
    $title = "Weekly Overview";
    $stylesheet = "main";

    date_default_timezone_set('Europe/London');
    require_once "../includes/header.inc.php";
    require_once "../includes/dbh.inc.php";
    require_once "../includes/classes.php";

    $dbh = new Dbh();
    $date = new Calender();
    $currentDate = escape(Input::get('y-m-d', date('Y-m-d')));

    if ($date->validateDate($currentDate)) {
        $date->setDates($currentDate);
    } else {
        header("Location: /zero-hour-contractor/app/overview?url=invalid");        
        exit();
    }

    $cover = new Cover();

    if (Session::exists('user')) {
        $employee = new Employee();
        $user = $employee->getByID(Session::get('user')); // get the user (who is accessing the page) data
        
        $organization = new Company();
        $company = $organization->getByID($user->CompanyID);
        $eResult = $employee->getFromCuid($company->CompanyID); // get all employees of a specific company, to be displayed in the overview 

        $departments = new Department();
        $department = $departments->getDepByComp($company->CompanyID);

        $tile = new HourTile();

        if (Input::exists('delete')) { // Delete a request 
            try {
                $tile->delete($_POST['id']);
            } catch (Exception $e) {
                die($e->getMessage());
            }         
        } else if (Input::exists('submit')) { // Add (create) and insert a new request
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'id' => array('required' => true),
                'department' => array('required' => true),
                'start' => array(
                    'required' => true,
                    'time' => true
                ),
                'end' => array(
                    'required' => true,
                    'time' => true
                ),
                'date' => array(
                    'required' => true,
                    'date' => true
                ),
                'desc' => array('max' => 50)
            ));

            if ($validation->passed()) {
                try {
                    $tile->create(array(
                        'EmployeeID' => $_POST['id'],
                        'DepartmentID' => $_POST['department'],
                        'StartTime' => $_POST['start'],
                        'EndTime' => $_POST['end'],
                        'BookDate' => $_POST['date'],
                        'Description' => $_POST['desc']
                    ));
                    $_POST = array();
                    header("Location: overview?y-m-d={$currentDate}"); // Maybe replace this with the random number session technique?
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                foreach($validation->getErrors() as $error) { // Put this code whereever I want to display errors
                    $_SESSION['errors'][] = $error;
                    header("Location: overview?y-m-d={$currentDate}"); // find out way to display error in url
                }
            }
        } else if (Input::exists('update')) { // Update a request
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'id' => array('required' => true),
                'department' => array('required' => true),
                'start' => array(
                    'required' => true,
                    'time' => true
                ),
                'end' => array(
                    'required' => true,
                    'time' => true
                ),
                'desc' => array('max' => 50)
            ));

            if ($validation->passed()) {
                try {
                    $tile->update($_POST['id'], array(
                        'DepartmentID' => $_POST['department'],
                        'StartTime' => $_POST['start'],
                        'EndTime' => $_POST['end'],
                        'Description' => $_POST['desc']
                    ));
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                foreach($validation->getErrors() as $error) {
                    $_SESSION['errors'][] = $error;
                }
            }
        }
    } else {
        header("Location: /zero-hour-contractor/index?login=nologin");
        exit();
    }
?>


<header id="header__overview-header">
    <nav class="overview-header__nav">
        <form action="" method="GET" autocomplete="off">
            <button name="y-m-d" value="<?php echo $date->decrementWeek('Y') . '-' . $date->decrementWeek('m') . '-' . $date->decrementWeek('d'); ?>" class="prev"></button>
        </form>

        <span class="overview-header__carousel">
            <?php 
                $date->setDate($company->CompanyStartDay);
                $month = $date->getDate('M');
                echo $date->getDate('dS') . " - "; 

                $numDays = ($company->CompanyEndDay - $company->CompanyStartDay) + 1;
                $date->setDate($company->CompanyEndDay);
                if ($date->getDate('M') !== $month) {
                    $month .= " - " . $date->getDate('M');
                }
                echo $date->getDate('dS') . " (" . $month . ") " . $date->getDate('Y');
            ?>                 
        </span>

        <form action="" method="GET" autocomplete="off">
            <button name="y-m-d" value="<?php echo $date->incrementWeek('Y') . '-' . $date->incrementWeek('m') . '-' . $date->incrementWeek('d'); ?>" class="next"></button>
        </form>
        
  
        <form action="" method="GET" autocomplete="off" id="date-selector">
            <input type="hidden" id="datepicker" class="<?php echo $currentDate; ?>" name="y-m-d" placeholder="Select a date">
        </form>
    </nav>
</header>



<!-- Side nav was here -->

<div id="overview-manager" class="overview-manager__container">
    <div class="overview-manager__table">
        <!-- Table header for column nams -->
        <div class="overview-manager__row overview-manager__row--header">
            <div class="overview-manager__cell overview-manager__cell--header <?php echo "day" . $numDays; ?>">
                <div class="cell__content"><div class="cell__text-content"><span>Name</span></div></div>
            </div>

            <?php
                for ($i = $company->CompanyStartDay; $i < $company->CompanyEndDay + 1; $i++) {        
                    $date->setDate($i);       
            ?>
                <div class="overview-manager__cell overview-manager__cell--header <?php echo $date->checkToday("overview-manager__cell--today ", "") . "day" . $numDays; ?>">
                    <div class="cell__content">
                        <div class="cell__text-content">
                            <span><?php echo  $date->getDate('D'); ?></span>
                            <span class="day"><?php echo $date->getDate('d'); ?></span>
                        </div>
                    </div>
                </div>
            <?php 
                } 
            ?>
        </div>     

        <?php        
            if ($eResult) {
                foreach ($eResult as $emp) {
        ?>
            <div class="overview-manager__row <?php if ($emp->EmployeeID == $user->EmployeeID) { echo "user"; } ?>">
                <div class="overview-manager__cell <?php echo "day" . $numDays; ?>">
                    <div class="cell__content cell__content--first">
                        <div class="cell__text-content">
                            <img src="../media/img/icons/profile.jpg" class="user-pic" />
                            <span><?php echo $emp->EmployeeFirst; ?></span>
                        </div>
                    </div>
                </div>

                <?php                                  
                    for ($i = $company->CompanyStartDay; $i < ($company->CompanyEndDay + 1); $i++) {
                        $date->setDate($i);
                        $hours = new HourTile();
                        $requests = $hours->getByIdDate($emp->EmployeeID, $date->getDate());

                        if ($requests) {
                            foreach ($requests as $key => $value) {
                                $cover->addShift($date->getDate('D'), $hours->getStart($value), $hours->getEnd($value), $value->DepartmentID);
                            }
                
                            // hResult is to show the first shift, if there are multiple shifts by one member on the same day
                            $hResult = $requests[0]; // Always show the first shift, if a member has multiple shifts on one day
                ?>
                    <div class="overview-manager__cell overview-manager__cell--button <?php echo $date->checkToday("overview-manager__cell--today ", "") . "day" . $numDays; ?>">
                        <div class="cell__content cell__content--dropdown">
                            <div class="dropdown__container">
                                <div class="cell__text-content cell__text-content--responsive"> 
                                    <span>
                                        <img src="../media/img/icons/clock-white.png" alt="Clock time icon" class="img-small" />
                                        <?php echo $hours->getStart($hResult); ?> - <?php echo $hours->getEnd($hResult); ?>
                                    </span>
                                    <span>
                                        <img src="../media/img/icons/department-white.png" alt="Department icon" class="img-small" />
                                        <?php echo $hours->getDepartment($hResult->DepartmentID, $department); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        
                        <!-- Notifications for the dropdown menus -->
                        <div class="notifications">
                            <?php 
                                if (count($requests) > 1) { 
                            ?>
                                <div class="notification-bubble">+<?php echo count($requests) - 1;?>
                                    <span class="tool-tip">+<?php echo count($requests) - 1;?> Other shifts</span>
                                </div>
                            <?php
                                } 
                                if ($date->checkToday() && $emp->EmployeeID == $user->EmployeeID) {                                  
                            ?>
                                <div class="notification-bubble">
                                    <span class="tool-tip">You have shifts today!</span>
                                    !
                                </div>
                            <?php 
                                } 
                                $testcount = 0;
                                foreach ($requests as $hour) {
                                    $hResult = $hour;
                                    if ($hResult->Description !== "") {       
                                        $testcount += 1; // Work out how many message boxes there are
                                    }
                                }

                                if ($testcount > 0) {
                            ?>
                                <div class="notification-bubble notification-bubble--desc">
                                    <span class="tool-tip">Shifts have reminders</span>
                                    <img src="../media/img/icons/description.png" alt="Description icon" />
                                </div>
                            <?php            
                                } 
                            ?>
                        </div>
                        <!-- End of Notification bubbles -->


                        <div class="cell__dropdown">
                            <div class="cell__text-content cell__text-content--index">
                                <span>
                                    <img src="../media/img/icons/user.png" alt="User icon" class="img-small" />
                                    <?php echo $employee->getFullName($emp); ?>
                                </span>
                            </div>
                            <div class="cell__text-content cell__text-content--index">
                                <span>
                                    <img src="../media/img/icons/day.png" alt="Day icon" class="img-small" />
                                    <?php echo $date->getDate(); ?>
                                </span>
                            </div>

                            <?php 
                                if (count($requests) > 1) {
                                    foreach ($requests as $key => $hour) { // Only loop through if there is more than 1
                                        $hResult = $hour;
                            ?>
                                <div class="cell__text-content cell__text-content--index">
                                    <span><?php echo "Shift " . ($key + 1) . ": "; ?></span>
                                </div>
                            <?php
                                        include "../includes/tile-dropdown.inc.php"; 
                                    }
                                } else{
                                    include "../includes/tile-dropdown.inc.php"; 
                                }  

                                if ($date->getDate() >= $date->getToday()) {
                            ?>       
                                <span class="dropdown__button dropdown__button--add modal__open"> <!-- Book more hours onto tile (anchor point) -->
                                    <img src="../media/img/icons/plus.png" alt="Add new hours icon" />
                                </span>

                                <div class="modal__full"> <!-- Fixx!!!! -->
                                    <div class="modal__container">
                                        <div class="modal__content">
                                            <div class="modal__title">
                                                <span>Request new hours for <?php echo $employee->getFullName($emp); ?> on:</span>
                                                <span><?php echo $date->getDateVerbal(); ?></span>                        
                                            </div>

                                            <div class="modal__desc">
                                                <form action="" method="POST" autocomplete="off" class="modal__form">                                       
                                                    <input type="hidden" name="uid" value="<?php $emp->EmployeeID; ?>" />
                                                    <label class="modal-form__tag modal-form__tag--required"><span>*</span> Indicates required field</label>
                                                    <div class="modal-form--left">
                                                        <!--Department field -->
                                                        <div class="modal-form__field">
                                                            <label class="modal-form__tag">Department <span>*</span></label>
                                                            <select class="modal-form__input department" name="department" placeholder="Department">
                                                                <option value="" disabled selected>Please select</option>
                                                                <?php
                                                                    foreach ($department as $dep) {
                                                                ?>
                                                                    <option value="<?php echo $dep->DepartmentID; ?>"><?php echo $dep->DepartmentName; ?></option>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </select> 
                                                            <label class="modal-form__tag--error departmentError">* Choose a Department</label>
                                                        </div>

                                                        <!--Time fields -->
                                                        <div class="modal-form__field">
                                                            <div class="modal-form__field--time">
                                                                <div class="modal-form__time">
                                                                    <label class="modal-form__tag modal-form__tag--time">Start Time <span>*</span></label>
                                                                    <input class="modal-form__input modal-form__input--time start" type="time" name="start" 
                                                                        min="<?php echo $organization->getStart($company); ?>" max="<?php echo $organization->getStop($company); ?>" 
                                                                        value="<?php echo $organization->getStart($company); ?>" />
                                                                    <label class="modal-form__tag--error startError">* Enter a valid Time</label>                
                                                                </div>

                                                                <div class="modal-form__time">
                                                                    <label class="modal-form__tag modal-form__tag--time">End Time <span>*</span></label>
                                                                    <input class="modal-form__input modal-form__input--time end" type="time" name="end" 
                                                                        min="<?php echo $organization->getStart($company); ?>" max="<?php echo $organization->getStop($company); ?>" 
                                                                        value="<?php echo $organization->getStop($company); ?>" />
                                                                    <label class="modal-form__tag--error endError" >* Enter a valid Time</label>                                                             
                                                                </div>
                                                            </div>
                                                            <label class="modal-form__tag--error timeError"></label>   
                                                        </div>
                                                        
                                                        <!--Reminder field (optional)-->
                                                        <div class="modal-form__field">
                                                            <label class="modal-form__tag">Reminder</label>
                                                            <input class="modal-form__input modal-form__input--desc" type="text" name="desc" />
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="date" value="<?php echo $date->getDate(); ?>" />
                                                    <input type="hidden" name="id" value="<?php echo $emp->EmployeeID; ?>" />
                                                    <button name="submit" class="modal-form__add submit">Submit</button>
                                                </form>                              
                                            </div>
                                            <span class="modal__close">×</span>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                }
                            ?>                                           
                            <span class="dropdown__button dropdown__button--return">
                                <img src="../media/img/icons/arrow.png" alt="Close dropdown icon" />
                            </span>
                        </div>
                    </div>

                    
                <?php 
                    } else { // No results found within employee row (no booked hours) 
                        if ($date->getDate() >= $date->getToday()) {
                ?>
                    <div class="overview-manager__cell overview-manager__cell--button overview-manager__cell--empty <?php echo $date->checkToday("overview-manager__cell--today ", "") . "day" . $numDays; ?>">
                        <div class="cell__content modal__open"> </div>

                        <div class="modal__full">
                            <div class="modal__container">
                                <div class="modal__content">
                                    <div class="modal__title">
                                        <span>Request hours for <?php echo $employee->getFullName($emp); ?> on:</span>
                                        <span><?php echo $date->getDateVerbal(); ?></span>                        
                                    </div>

                                    <div class="modal__desc">
                                        <?php                                            
                                            if (!$department) { // If there are no departments for that specific company     
                                                if ($user->EmployeeType == 'admin') {                                 
                                        ?>
                                            <p>You currently have no department to bind hours to.</p>
                                            <span class="modal__form">
                                                <a href="department" class="modal-form__add">Add Departments</a>
                                            </span>
                                        <?php
                                                } else {
                                        ?>
                                            <p>You currently have no department to bind hours to.</p>
                                            <p>You must wait for an admin to create some.</p>
                                        <?php
                                                }
                                            } else {
                                        ?>
                                        <form action="" method="POST" autocomplete="off" class="modal__form">                                       
                                            <input type="hidden" name="uid" value="<?php $emp->EmployeeID; ?>" />
                                            <label class="modal-form__tag modal-form__tag--required"><span>*</span> Indicates required field</label>
                                            <div class="modal-form--left">
                                                <!--Department field -->
                                                <div class="modal-form__field">
                                                    <label class="modal-form__tag">Department <span>*</span></label>
                                                    <select class="modal-form__input department" name="department" placeholder="Department" >
                                                        <option value="" disabled selected>Please select</option>
                                                        <?php
                                                            foreach ($department as $dep) {
                                                        ?>
                                                            <option value="<?php echo $dep->DepartmentID; ?>"><?php echo $dep->DepartmentName; ?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select> 
                                                    <label class="modal-form__tag--error departmentError">* Choose a Department</label>
                                                </div>

                                                <!--Time fields -->
                                                <div class="modal-form__field">
                                                    <div class=" modal-form__field--time">
                                                        <div class="modal-form__time">
                                                            <label class="modal-form__tag modal-form__tag--time">Start Time <span>*</span></label>
                                                            <input class="modal-form__input modal-form__input--time start" type="time" name="start" 
                                                                min="<?php echo $organization->getStart($company); ?>" max="<?php echo $organization->getStop($company); ?>" 
                                                                value="<?php echo $organization->getStart($company); ?>" />
                                                            <label class="modal-form__tag--error startError">* Enter a valid Time</label>
                                                        </div>

                                                        <div class="modal-form__time">
                                                            <label class="modal-form__tag modal-form__tag--time">End Time <span>*</span></label>
                                                            <input class="modal-form__input modal-form__input--time end" type="time" name="end" 
                                                                min="<?php echo $organization->getStart($company); ?>" max="<?php echo $organization->getStop($company); ?>" 
                                                                value="<?php echo $organization->getStop($company) ?>" />
                                                            <label class="modal-form__tag--error endError">* Enter a valid Time</label>                                                    
                                                        </div>     
                                                    </div>                                            
                                                    <label class="modal-form__tag--error timeError"></label>   
                                                </div>
                                                    
                                                <!--Reminder field (optional)-->
                                                <div class="modal-form__field">
                                                    <label class="modal-form__tag">Reminder</label>
                                                    <input class="modal-form__input modal-form__input--desc" type="text" name="desc" />
                                                </div>
                                            </div>

                                            <input type="hidden" name="date" value="<?php echo $date->getDate(); ?>" />
                                            <input type="hidden" name="id" value="<?php echo $emp->EmployeeID; ?>" />
                                            <button name="submit" class="modal-form__add submit">Submit</button>
                                        </form>
                                        <?php 
                                            }
                                        ?>
                                    </div>
                                    <span class="modal__close">×</span>
                                </div>
                            </div>
                        </div>

                        <?php 
                            if ($date->checkToday() && $emp->EmployeeID == $user->EmployeeID) { 
                        ?> 
                            <div class="notifications">
                                <div class="notification-bubble notification-bubble--today">
                                    <span class="tool-tip">No shifts for you today</span>
                                    <img src="../media/img/icons/today.png" alt="Description icon" />
                                </div>
                            </div>                         
                        <?php 
                            } 
                        ?>
                    </div>
                <?php
                    } else { // It is before today so user shouldnt't be able to add hours 
                ?>
                    <div class="overview-manager__cell overview-manager__cell--button overview-manager__cell--before <?php echo "day" . $numDays; ?>">
                        <div class="cell__content"> </div>
                    </div>

                <?php
                            }
                        }
                    }
                ?>
            </div>

        <?php
                }
            } else { // No results found for employees (no employees)
                echo "there is nothing to display here!";
            }
        ?>
    </div>  
    
    <div id="footer__overview-footer">
        This is where the temporary cover needed details will be placed<br><br>
        <b>
        <?php
            $cStart = new DateTime($company->CompanyStart);
            $cEnd = new DateTime($company->CompanyStop);

            $result = $cover->getShifts($cStart, $cEnd);

            if ($result && count($result) > 0) {
                foreach ($result as $c) {   
                    echo "on " . $c->getDay() . " in department " . $hours->getDepartment($c->getDepartment(), $department);
                    echo " ";                      
                    print_r($c->getStartTime());
                    echo " - ";
                    print_r($c->getEndTime());
                    echo "<br> <br>";
                }
            }

            echo "<br> <br>";
            echo "<br> <br>";
        ?>
        </b>
    </div>
</div>

<?php 
    if (Session::exists('errors')) {
        $message = Session::get('errors')[0];
?>
        <div class="modal__full modal__full--error">
            <div class="modal__container">
                <div class="modal__content">
                    <div class="modal__title">
                        <span>Warning Message</span>                     
                    </div>
                    <div class="modal__desc">
                        <p>Input failed:</p>
                        <?php echo $message; ?>
                    </div>
                    <span class="modal__close">×</span>
                </div>
            </div>
        </div>
<?php
    }
    Session::delete('errors');
?>

<?php require_once "../includes/side-nav.inc.php"; ?>

<?php 
    require_once "../includes/footer.inc.php";
?>

        