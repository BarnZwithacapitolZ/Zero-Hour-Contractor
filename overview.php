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
    $today = "";
    $days = 7; // For changing when the company is open (might only be 5 days a week etc.)

    if (isset($_SESSION['u_id'])) {
        $user = new Employee();
        $user->setByParams($_SESSION['u_id'], $_SESSION['u_first'], $_SESSION['u_last'], $_SESSION['u_type'], 
            $_SESSION['u_payrate'], $_SESSION['u_email'], $_SESSION['u_cuid']);
    } else {
        header("Location: index?login=nologin");
        exit();
    }
?>

        <header id="header__overview-header">
            <nav class="overview-header__nav">
                <span><</span><span>This Week</span><span>></span>
                <?php echo $user->getName(); ?>
            </nav>
        </header>

        <?php require_once "includes/side-nav.inc.php" ?>

        <div id="overview-manager" class="overview-manager__container">
            <div class="overview-manager__table">
                <!-- Table header for column nams -->
                <div class="overview-manager__row overview-manager__row--header">
                    <div class="overview-manager__cell overview-manager__cell--header">
                        <div class="cell__content"><div class="cell__text-content"><span>Name</span></div></div>
                    </div>

                    <?php
                        for ($i = 0; $i < $days; $i++) {        
                            $date = new DateTime(date('Y-m-d', strtotime('monday ' . $week . ' week') + (($i + 1) * 86300)));              
                    ?>
                    <div class="overview-manager__cell overview-manager__cell--header <?php if ($date->format('Y-m-d') == date('Y-m-d')) { echo "overview-manager__cell--today"; }?>">
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
                    $orgID = $user->getOrgID();
                    $query = "SELECT EmployeeID, OrganizationID, EmployeeFirst, EmployeeLast, EmployeeType, 
                        EmployeePayrate, EmployeeEmail FROM tblemployee WHERE OrganizationID='$orgID'";
                    $empResult = $dbh->executeSelect($query);
                    if ($empResult) {
                        foreach ($empResult as $emp) {
                            $employee = new Employee();
                            $employee->setByRow($emp);
                ?>
                <div class="overview-manager__row <?php if ($employee->getID() == $user->getID()) { echo "user"; } ?>">
                    <div class="overview-manager__cell">
                        <div class="cell__content cell__content--first">
                            <div class="cell__text-content">
                                <img src="media/img/icons/profile.jpg" class="user-pic" />
                                <span><?php echo $employee->getName(); ?></span>
                            </div>
                        </div>
                    </div>

                    <?php                                   
                            for ($i = 1; $i < $days + 1; $i++) {
                                $select = $employee->getID();
                                $date = date('Y-m-d', strtotime('monday ' . $week . ' week') + ($i * 86300)); // 86300 for new day
                                
                                $query = "SELECT * FROM tblbook WHERE BookDate='$date' AND EmployeeID='$select' ORDER BY StartTime, EndTime";
                                $bookResult = $dbh->executeSelect($query);
                                if ($bookResult) {
                                    $bookedHours = new HourTile();
                                    $bookedHours->setByRow($bookResult[0]); // Only show the first result of any day
                    ?>
                    <div class="overview-manager__cell overview-manager__cell--button <?php if ($date == date('Y-m-d')) { echo "overview-manager__cell--today"; }?>">
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
                        
                        <?php if (count($bookResult) > 1) { ?>
                            <div class="notification-bubble">+<?php echo count($bookResult) - 1;?></div>
                        <?php } ?>

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
                    <div class="overview-manager__cell overview-manager__cell--button overview-manager__cell--empty <?php if ($date == date('Y-m-d')) { echo "overview-manager__cell--today"; }?>">
                        <div class="cell__content"></div>
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

        