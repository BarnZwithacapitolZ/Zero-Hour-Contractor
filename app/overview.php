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
    $date->setWeek("this");   
     // For changing when the company is open (might only be 5 days a week etc.)

    $cover = array();

    if (Session::exists('user')) {
        $employee = new Employee();
        $user = $employee->getByID(Session::get('user')); // get the user (who is accessing the page) data
        
        $organization = new Company();
        $company = $organization->getByID($user->CompanyID);
        $eResult = $employee->getFromCuid($company->CompanyID); // get all employees of a specific company, to be displayed in the overview 

        $departments = new Department();
        $department = $departments->getDepByComp($company->CompanyID);

        if (Input::exists('delete')) {
            $tile = new HourTile();
            try {
                $tile->delete($_POST['id']);
            } catch(Exception $e) {
                die($e->getMessage());
            }         
        } else if (Input::exists('submit')) {
            return;
        } else if (Input::exists('update')) {
            return;
        }

    } else {
        header("Location: /zero-hour-contractor/index?login=nologin");
        exit();
    }
?>

<header id="header__overview-header">
    <nav class="overview-header__nav">
        <span><</span>
        <span>
            <?php 
                $date->setDate($company->CompanyStartDay);
                $month = $date->getDate('M');
                echo $date->getDate('dS') . " - "; 

                $numDays = ($company->CompanyEndDay - $company->CompanyStartDay) + 1;
                $date->setDate($company->CompanyEndDay);
                if ($date->getDate('M') !== $month) {
                    $month .= " - " . $date->getDate('M');
                }
                echo $date->getDate('dS') . " (" . $month . ")";
            ?>                 
        </span>
        <span>></span>
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

                    foreach ($department as $dep) {
                        $cover[$date->getDate('D')][$dep->DepartmentID] = array();
                    }
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
                                $cover[$date->getDate('D')][$value->DepartmentID][] = array($hours->getStart($value), $hours->getEnd($value), $value->BookID);
                            }

                            $hResult = $requests[0];
                ?>
                    <div class="overview-manager__cell overview-manager__cell--button <?php echo $date->checkToday("overview-manager__cell--today ", "") . "day" . $numDays; ?>">
                        <div class="cell__content cell__content--dropdown">
                            <div class="cell__text-content cell__text-content--responsive"> 
                                <span>
                                    <img src="../media/img/icons/clock.png" alt="Clock time icon" class="img-small" />
                                    <?php echo $hours->getStart($hResult); ?> - <?php echo $hours->getEnd($hResult); ?>
                                </span>
                                <span>
                                    <img src="../media/img/icons/department.png" alt="Department icon" class="img-small" />
                                    <?php echo $hours->getDepartment($hResult->DepartmentID, $department); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="notifications">
                            <?php 
                                if (count($requests) > 1) { 
                            ?>
                                <div class="notification-bubble">+<?php echo count($requests) - 1;?></div>
                            <?php
                                } 
                                if ($date->checkToday() && $emp->EmployeeID == $user->EmployeeID) {                                  
                            ?>
                                <div class="notification-bubble">!</div>
                            <?php 
                                } 
                                foreach ($requests as $hour) {
                                    $hResult = $hour;
                                    if ($hResult->Description !== "") {                           
                            ?>
                                <div class="notification-bubble notification-bubble--desc">
                                    <img src="../media/img/icons/description.png" alt="Description icon" />
                                </div>
                            <?php 
                                    }
                                } 
                            ?>
                        </div>

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

                                <div class="modal__full">
                                    <div class="modal__container">
                                        <div class="modal__content">
                                            <div class="modal__title">
                                                <span><b>Request new hours for <?php echo $employee->getFullName($user); ?> on <?php echo $date->getDateVerbal(); ?>:</b></span>                        
                                            </div>

                                            <div class="modal__desc">
                                                <form action="#hourModal" method="POST" autocomplete="off" class="modal__form">                                       
                                                    <input type="hidden" name="uid" value="<?php $emp->EmployeeID; ?>" />
                                                    <div class="modal-form--left">
                                                        <span class="modal-form__tag">Department:</span>
                                                        <select class="modal-form__input" name="department" placeholder="Department">
                                                            <option value="" disabled selected>Please select</option>
                                                            <?php
                                                                foreach ($department as $dep) {
                                                            ?>
                                                                <option value="<?php echo $dep->DepartmentID; ?>"><?php echo $dep->DepartmentName; ?></option>
                                                            <?php
                                                                }
                                                            ?>
                                                        </select> 

                                                        <div class="modal-form__time">
                                                            <span class="modal-form__tag modal-form__tag--time">Start Time:</span>
                                                            <input class="modal-form__input modal-form__input--time" type="time" name="start" min="<?php echo $company->CompanyStart; ?>" max="<?php echo $company->CompanyStop; ?>" value="<?php echo escape(Input::get('start', $company->CompanyStart)); ?>" />
                                                        </div>

                                                        <div class="modal-form__time">
                                                            <span class="modal-form__tag modal-form__tag--time">End Time:</span>
                                                            <input class="modal-form__input modal-form__input--time" type="time" name="end" min="<?php echo $company->CompanyStart; ?>" max="<?php echo $company->CompanyStop; ?>" value="<?php echo escape(Input::get('stop', $company->CompanyStop)); ?>" />
                                                        </div>
                                                        
                                                        <input type="hidden" name="date" value="<?php $date->getDate(); ?>" />

                                                        <span class="modal-form__tag">Reminder (optional):</span>
                                                        <input class="modal-form__input modal-form__input--desc" type="text" name="desc"  value="<?php echo escape(Input::get('desc')); ?>" />
                                                    </div>
                                                    <button name="submit" class="modal-form__add">Submit</button>
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
                                        <span><b>Request hours for <?php echo $employee->getFullName($user); ?> on <?php echo $date->getDateVerbal(); ?>:</b></span>                        
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
                                        <form action="#hourModal" method="POST" autocomplete="off" class="modal__form">                                       
                                            <input type="hidden" name="uid" value="<?php $emp->EmployeeID; ?>" />
                                            <div class="modal-form--left">
                                                <span class="modal-form__tag">Department:</span>
                                                <select class="modal-form__input" name="department" placeholder="Department" >
                                                    <option value="" disabled selected>Please select</option>
                                                    <?php
                                                        foreach ($department as $dep) {
                                                    ?>
                                                        <option value="<?php echo $dep->DepartmentID; ?>"><?php echo $dep->DepartmentName; ?></option>
                                                    <?php
                                                        }
                                                    ?>
                                                </select> 
                                                <div class="modal-form__time">
                                                    <span class="modal-form__tag modal-form__tag--time">Start Time:</span>
                                                    <input class="modal-form__input modal-form__input--time" type="time" name="start" min="<?php echo $company->CompanyStart; ?>" max="<?php echo $company->CompanyStop; ?>" value="<?php echo escape(Input::get('start', $company->CompanyStart)); ?>" />
                                                </div>

                                                <div class="modal-form__time">
                                                    <span class="modal-form__tag modal-form__tag--time">End Time:</span>
                                                    <input class="modal-form__input modal-form__input--time" type="time" name="end" min="<?php echo $company->CompanyStart; ?>" max="<?php echo $company->CompanyStop; ?>" value="<?php echo escape(Input::get('stop', $company->CompanyStop)); ?>" />
                                                </div>

                                                <input type="hidden" name="date" value="<?php $date->getDate(); ?>" />
                                                <span class="modal-form__tag">Reminder (optional):</span>
                                                <input class="modal-form__input modal-form__input--desc" type="text" name="desc" value="<?php echo escape(Input::get('desc')); ?>" />
                                            </div>
                                            <button name="submit" class="modal-form__add">Submit</button>
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
            function my_sort($a,$b)
            {
                if ($a[0]==$b[0]) return 0;
                return ($a[0] < $b[0])?-1:1;
            }

            $cStart = new DateTime($company->CompanyStart);
            $cEnd = new DateTime($company->CompanyStop);

            function check_gaps($shifts, $start, $end) {
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
                    //$diff = $last->diff($shift[0])->format('%R%h:%i');

                    if ($shift[0] > $last) { 
                        $cases[] = array($last->format('h:i'), $shift[0]->format('h:i'));
                    }
                    $last = $shift[1];
                    $lastShift = array($shift[0], $shift[1]);
                }

                $diff = $last->diff($end)->format('%R%h:%i');
                if ($end > $last) {
                    $cases[] = array($last->format('h:i'), $end->format('h:i'));
                }
                return $cases;
            }

            // If there are no hours available on a specific day, set the hours available to (for each (total working hours / max hours ))
            foreach ($cover as $day => $shift) {
                foreach ($shift as $key => $dep) {
                    $full = array();
                    foreach($dep as $time) {
                        $start = new DateTime($time[0]);
                        $end = new DateTime($time[1]);
                        $full[] = array($start, $end);
                    }
                    usort($full,"my_sort");
                    $cases = check_gaps($full, $cStart, $cEnd);
                    if (count($cases) > 0) {
                        echo "on " . $day . " in department " . $hours->getDepartment($key, $department);
                        foreach ($cases as $case) {   
                            echo " ";                      
                            print_r($case[0]);
                            echo " - ";
                            print_r($case[1]);
                        }
                        echo "<br> <br>";
                    }                  
                }
            }
        ?>
        </b>
    </div>
</div>

<?php require_once "../includes/side-nav.inc.php" ?>

<?php 
    require_once "../includes/footer.inc.php";
?>

        