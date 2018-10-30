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

    if (Session::exists('user')) {
        $user = new Employee();
        $user->setByID(Session::get('user'));

        $company = new Company();
        $company->setByID($user->getCUID());

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
                        $date->setDate($company->getStart());
                        $month = $date->getDate('M');
                        echo $date->getDate('dS') . " - "; 

                        $date->setDate($company->getEnd());
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
                    <div class="overview-manager__cell overview-manager__cell--header <?php echo $company->getDays(); ?>">
                        <div class="cell__content"><div class="cell__text-content"><span>Name</span></div></div>
                    </div>

                    <?php
                        for ($i = $company->getStart(); $i < $company->getEnd() + 1; $i++) {        
                            $date->setDate($i); 
                    ?>
                    <div class="overview-manager__cell overview-manager__cell--header <?php echo $date->getToday("overview-manager__cell--today ", "") . $company->getDays(); ?>">
                        <div class="cell__content">
                            <div class="cell__text-content">
                                <span><?php echo  $date->getDate('D'); ?></span>
                                <span class="day"><?php echo $date->getDate('d'); ?></span>
                            </div>
                        </div>
                    </div>
                        <?php } ?>
                </div>     

                <?php
                    $query = strtr(
                        "SELECT 
                            EmployeeID, 
                            CompanyID, 
                            EmployeeFirst, 
                            EmployeeLast, 
                            EmployeeType, 
                            EmployeePayrate, 
                            EmployeeEmail 
                        FROM tblemployee 
                        WHERE CompanyID=':cuid' 
                        ORDER BY EmployeeID DESC",
                        [":cuid" => $company->getID()]
                    );

                    $empResult = $dbh->executeSelect($query);
                    if ($empResult) {
                        foreach ($empResult as $emp) {
                            $employee = new Employee();
                            $employee->setByRow($emp);
                ?>
                <div class="overview-manager__row <?php if ($employee->getID() == $user->getID()) { echo "user"; } ?>">
                    <div class="overview-manager__cell <?php echo $company->getDays(); ?>">
                        <div class="cell__content cell__content--first">
                            <div class="cell__text-content">
                                <img src="../media/img/icons/profile.jpg" class="user-pic" />
                                <span><?php echo $employee->getName(); ?></span>
                            </div>
                        </div>
                    </div>

                    <?php                                   
                            for ($i = $company->getStart(); $i < ($company->getEnd() + 1); $i++) {
                                $date->setDate($i); // 86300 for new day
                                
                                $query = strtr(
                                    "SELECT * 
                                    FROM tblbook 
                                    WHERE BookDate=':date' 
                                    AND EmployeeID=':id' 
                                    ORDER BY 
                                            StartTime, 
                                            EndTime",
                                    [
                                        ":date" => $date->getDate('Y-m-d'),
                                        ":id" => $employee->getID()
                                    ]
                                );

                                $bookResult = $dbh->executeSelect($query);
                                if ($bookResult) {
                                    $bookedHours = new HourTile();
                                    $bookedHours->setByRow($bookResult[0]); // Only show the first result of any day
                    ?>
                    <div class="overview-manager__cell overview-manager__cell--button <?php echo $date->getToday("overview-manager__cell--today ", "") . $company->getDays(); ?>">
                        <div class="cell__content cell__content--dropdown">
                            <div class="cell__text-content cell__text-content--responsive"> 
                                <span>
                                    <img src="../media/img/icons/clock.png" alt="Clock time icon" class="img-small" />
                                    <?php echo $bookedHours->getStart(); ?> - <?php echo $bookedHours->getEnd(); ?>
                                </span>
                                <span>
                                    <img src="../media/img/icons/department.png" alt="Department icon" class="img-small" />
                                    <?php echo $bookedHours->getDepartment(); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="notifications">
                        <?php if (count($bookResult) > 1) { ?>
                            <div class="notification-bubble">+<?php echo count($bookResult) - 1;?></div>
                        <?php
                            } 
                            if ($date->getToday() && $employee->getID() == $user->getID()) {
                         ?>
                            <div class="notification-bubble">!</div>
                        <?php 
                            } 
                            if ($bookedHours->getDesc() !== "") {
                        ?>
                            <div class="notification-bubble notification-bubble--desc">
                                <img src="../media/img/icons/description.png" alt="Description icon" />
                            </div>
                        <?php } ?>
                        </div>

                        <div class="cell__dropdown">
                            <div class="cell__text-content cell__text-content--index">
                                <span>
                                    <img src="../media/img/icons/user.png" alt="User icon" class="img-small" />
                                    <?php echo $employee->getName()?>
                                </span>
                            </div>
                            <div class="cell__text-content cell__text-content--index">
                                <span>
                                    <img src="../media/img/icons/day.png" alt="Day icon" class="img-small" />
                                    <?php echo $bookedHours->getDate()?>
                                </span>
                            </div>

                            <?php 
                                if (count($bookResult) > 1) {
                                    foreach ($bookResult as $key => $book) { // Only loop through if there is more than 1
                                        $bookedHours->setByRow($book); 
                            ?>
                            <div class="cell__text-content cell__text-content--index">
                                <span><?php echo "Shift " . ($key + 1) . ": "?></span>
                             </div>
                            <?php
                                        include "../includes/tile-dropdown.inc.php"; 
                                    }
                                } else{
                                    include "../includes/tile-dropdown.inc.php"; 
                                }  
                            ?>             
                            <span class="dropdown__button dropdown__button--add modal__open"> <!-- Book more hours onto tile (anchor point) -->
                                <img src="../media/img/icons/plus.png" alt="Add new hours icon" />
                            </span>
                            <div class="modal__full">
                                <div class="modal__container">
                                    <div class="modal__content">
                                        <div class="modal__title">
                                            <span><b>Request new hours for <?php echo $employee->getName("full"); ?> on <?php echo $date->getDateVerbal(); ?>:</b></span>                        
                                        </div>

                                        <div class="modal__desc">
                                            <form action="#hourModal" method="POST" autocomplete="off" class="modal__form">                                       
                                                <input type="hidden" name="uid" value="<?php $employee->getID(); ?>" />
                                                <div class="modal-form--left">
                                                    <span class="modal-form__tag">Department:</span>
                                                    <select class="modal-form__input" name="department" placeholder="Department">
                                                        <option value="" disabled selected>Please select</option>
                                                        <?php
                                                            foreach ($result as $dep) {
                                                        ?>
                                                            <option value="<?php echo $dep->DepartmentID; ?>"><?php echo $dep->DepartmentName; ?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select> 

                                                    <div class="modal-form__time">
                                                        <span class="modal-form__tag modal-form__tag--time">Start Time:</span>
                                                        <input class="modal-form__input modal-form__input--time" type="time" name="start" value="<?php echo escape(Input::get('start', '08:00')); ?>" />
                                                    </div>

                                                    <div class="modal-form__time">
                                                        <span class="modal-form__tag modal-form__tag--time">End Time:</span>
                                                        <input class="modal-form__input modal-form__input--time" type="time" name="end" value="<?php echo escape(Input::get('stop', '17:00')); ?>" />
                                                    </div>
                                                    
                                                    <input type="hidden" name="date" value="<?php $date->getDate(); ?>" />

                                                    <span class="modal-form__tag">Description (optional):</span>
                                                    <input class="modal-form__input modal-form__input--desc" type="text" name="desc"  value="<?php echo escape(Input::get('desc')); ?>" />
                                                </div>
                                                <button name="submit" class="modal-form__add">Submit</button>
                                            </form>                              
                                        </div>
                                        <span class="modal__close">×</span>
                                    </div>
                                </div>
                            </div>

                            <span class="dropdown__button dropdown__button--return">
                                <img src="../media/img/icons/arrow.png" alt="Close dropdown icon" />
                            </span>
                        </div>
                    </div>

                        
                    <?php } else { // No results found within employee row (no booked hours) ?>
                    <div class="overview-manager__cell overview-manager__cell--button overview-manager__cell--empty <?php echo $date->getToday("overview-manager__cell--today ", "") . $company->getDays(); ?>">
                        <div class="cell__content modal__open"> </div>

                        <div class="modal__full">
                            <div class="modal__container">
                                <div class="modal__content">
                                    <div class="modal__title">
                                        <span><b>Request hours for <?php echo $employee->getName("full"); ?> on <?php echo $date->getDateVerbal(); ?>:</b></span>                        
                                    </div>

                                    <div class="modal__desc">
                                        <?php 
                                            $department = new Department();
                                            $result = $department->getDepByComp($company->getID());

                                            if (!$result) { // If there are no departments for that specific company     
                                                if ($user->getType() == 'admin') {                                 
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
                                            <input type="hidden" name="uid" value="<?php $employee->getID(); ?>" />
                                            <div class="modal-form--left">
                                                <span class="modal-form__tag">Department:</span>
                                                <select class="modal-form__input" name="department" placeholder="Department" >
                                                    <option value="" disabled selected>Please select</option>
                                                    <?php
                                                        foreach ($result as $dep) {
                                                    ?>
                                                        <option value="<?php echo $dep->DepartmentID; ?>"><?php echo $dep->DepartmentName; ?></option>
                                                    <?php
                                                        }
                                                    ?>
                                                </select> 
                                                <div class="modal-form__time">
                                                    <span class="modal-form__tag modal-form__tag--time">Start Time:</span>
                                                    <input class="modal-form__input modal-form__input--time" type="time" name="start" value="<?php echo escape(Input::get('start', '08:00')); ?>" />
                                                </div>

                                                <div class="modal-form__time">
                                                    <span class="modal-form__tag modal-form__tag--time">End Time:</span>
                                                    <input class="modal-form__input modal-form__input--time" type="time" name="end" value="<?php echo escape(Input::get('stop', '17:00')); ?>" />
                                                </div>

                                                <input type="hidden" name="date" value="<?php $date->getDate(); ?>" />
                                                <span class="modal-form__tag">Description (optional):</span>
                                                <input class="modal-form__input modal-form__input--desc" type="text" name="desc" value="<?php echo escape(Input::get('desc')); ?>" />
                                            </div>
                                            <button name="submit" class="modal-form__add">Submit</button>
                                        </form>
                                        <?php }?>
                                    </div>
                                    <span class="modal__close">×</span>
                                </div>
                            </div>
                        </div>

                        <?php if ($date->getToday() && $employee->getID() == $user->getID()) { ?> 
                            <div class="notifications">
                                <div class="notification-bubble notification-bubble--today">
                                    <img src="../media/img/icons/today.png" alt="Description icon" />
                                </div>
                            </div>                         
                        <?php } ?>
                    </div>
                    <?php
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

            </div>
        </div>

        <?php require_once "../includes/side-nav.inc.php" ?>

<?php 
    require_once "../includes/footer.inc.php";
?>

        