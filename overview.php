<?php
    session_start();
    $title = "Weekly Overview";
    $stylesheet = "main";

    date_default_timezone_set('Europe/London');
    require_once "includes/header.inc.php";
    require_once "includes/dbh.inc.php";
    require_once "includes/classes.php";

    $dbh = new Dbh();
    $week = "this";
     // For changing when the company is open (might only be 5 days a week etc.)

    if (isset($_SESSION['user']) && isset($_SESSION['company'])) {
        $user = new Employee();
        $user->setByArray($_SESSION['user']);

        $company = new Company();
        $company->setByArray($_SESSION['company']);
    } else {
        header("Location: index?login=nologin");
        exit();
    }
?>

        <header id="header__overview-header">
            <nav class="overview-header__nav">
                <span><</span><span>This Week</span><span>></span>
            </nav>
        </header>

        <?php require_once "includes/side-nav.inc.php" ?>

        <div id="overview-manager" class="overview-manager__container">
            <div class="overview-manager__table">
                <!-- Table header for column nams -->
                <div class="overview-manager__row overview-manager__row--header">
                    <div class="overview-manager__cell overview-manager__cell--header <?php echo $company->getDays(); ?>">
                        <div class="cell__content"><div class="cell__text-content"><span>Name</span></div></div>
                    </div>

                    <?php
                        for ($i = $company->getStart(); $i < $company->getEnd() + 1; $i++) {        
                            $date = new DateTime(date('Y-m-d', strtotime('monday ' . $week . ' week') + ($i * 86300)));  
                            if ($date->format('Y-m-d') == date('Y-m-d')) {
                                $today = "overview-manager__cell--today";
                            } else {
                                $today = "";
                            }
                    ?>
                    <div class="overview-manager__cell overview-manager__cell--header <?php echo $today . " " . $company->getDays(); ?>">
                        <div class="cell__content">
                            <div class="cell__text-content">
                                <span><?php echo  $date->format('D'); ?></span>
                                <span class="day"><?php echo $date->format('d'); ?></span>
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
                                <img src="media/img/icons/profile.jpg" class="user-pic" />
                                <span><?php echo $employee->getName(); ?></span>
                            </div>
                        </div>
                    </div>

                    <?php                                   
                            for ($i = $company->getStart(); $i < ($company->getEnd() + 1); $i++) {
                                $date = date('Y-m-d', strtotime('monday ' . $week . ' week') + ($i * 86300)); // 86300 for new day

                                if ($date == date('Y-m-d')) {
                                    $today = "overview-manager__cell--today";
                                    //if ($employee->getID() == $user->getID()) {
                                        //$today .= " overview-manager__cell--user";
                                    //}
                                } else {
                                    $today = "";
                                }
                                
                                $query = strtr(
                                    "SELECT * 
                                    FROM tblbook 
                                    WHERE BookDate=':date' 
                                    AND EmployeeID=':id' 
                                    ORDER BY 
                                            StartTime, 
                                            EndTime",
                                    [
                                        ":date" => $date,
                                        ":id" => $employee->getID()
                                    ]
                                );

                                $bookResult = $dbh->executeSelect($query);
                                if ($bookResult) {
                                    $bookedHours = new HourTile();
                                    $bookedHours->setByRow($bookResult[0]); // Only show the first result of any day
                    ?>
                    <div class="overview-manager__cell overview-manager__cell--button <?php echo $today . " " . $company->getDays(); ?>">
                        <div class="cell__content cell__content--dropdown">
                            <div class="cell__text-content cell__text-content--responsive"> 
                                <span>
                                    <img src="media/img/icons/clock.png" alt="Clock time icon" class="img-small" />
                                    <?php echo $bookedHours->getStart(); ?> - <?php echo $bookedHours->getEnd(); ?>
                                </span>
                                <span>
                                    <img src="media/img/icons/department.png" clock="Department icon" class="img-small" />
                                    <?php echo $bookedHours->getDepartment(); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="notifications">
                        <?php if (count($bookResult) > 1) { ?>
                            <div class="notification-bubble">+<?php echo count($bookResult) - 1;?></div>
                        <?php
                            } 
                            if ($date == date('Y-m-d') && $employee->getID() == $user->getID()) {
                         ?>
                            <div class="notification-bubble">!</div>
                        <?php 
                            } 
                            if ($bookedHours->getDesc() !== "") {
                        ?>
                            <div class="notification-bubble notification-bubble--desc">
                                <img src="media/img/icons/description.png" alt="Description icon" />
                            </div>
                        <?php } ?>
                        </div>

                        <div class="cell__dropdown">
                            <div class="cell__text-content cell__text-content--index">
                                <span>
                                    <img src="media/img/icons/user.png" clock="Department icon" class="img-small" />
                                    <?php echo $employee->getName()?>
                                </span>
                            </div>
                            <div class="cell__text-content cell__text-content--index">
                                <span>
                                    <img src="media/img/icons/day.png" clock="Department icon" class="img-small" />
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
                                        include "includes/tile-dropdown.inc.php"; 
                                    }
                                } else{
                                    include "includes/tile-dropdown.inc.php"; 
                                }  
                            ?>             
                            <span class="dropdown__button dropdown__button--add"> <!-- Book more hours onto tile (anchor point) -->
                                <img src="media/img/icons/plus.png" alt="Add new hours icon" />
                            </span>
                            <span class="dropdown__button dropdown__button--return">
                                <img src="media/img/icons/arrow.png" alt="Close dropdown icon" />
                            </span>
                        </div>
                    </div>

                        
                    <?php } else { // No results found within employee row (no booked hours) ?>
                    <div class="overview-manager__cell overview-manager__cell--button overview-manager__cell--empty <?php echo $today . " " . $company->getDays(); ?>">
                        <div class="cell__content"> </div>
                        <?php if ($date == date('Y-m-d') && $employee->getID() == $user->getID()) { ?> 
                            <div class="notifications">
                                <div class="notification-bubble notification-bubble--today">
                                    <img src="media/img/icons/today.png" alt="Description icon" />
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

<?php 
    require_once "includes/footer.inc.php";
?>

        