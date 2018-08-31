<?php
    $title = "Overview Manager";

    date_default_timezone_set('Europe/London');
    require_once "includes/header.inc.php";
    require_once "includes/dbh.inc.php";
    require_once "includes/classes.php";

    $dbh = new Dbh();

    $week = "-3";
    $days = 7;
?>

        <header id="header__overview-header">
            <div class="header-contents">
                <span><</span><span>This Week</span><span>></span>
            </div>
        </header>

        <?php require_once "includes/side-nav.inc.php" ?>

        <div id="overview-manager">
            <div class="table-overview">
                <!-- Table header for column nams -->
                <div class="table-row row-header">
                    <div class="table-cell table-header">
                        <div class="cell-content"><div class="text-contents"><span>Name</span></div></div>
                    </div>

                    <?php
                        for ($i = 0; $i < $days; $i++) {        
                            $date = new DateTime(date('Y-m-d', strtotime('monday ' . $week . ' week') + (($i + 1) * 86300)));              
                    ?>
                    <div class="table-cell table-header <?php if ($date->format('Y-m-d') == date('Y-m-d')) { echo "today"; }?>">
                        <div class="cell-content">
                            <div class="text-contents">
                                <span><?php echo  $date->format('D'); ?></span>
                                <span class="day"><?php echo $date->format('d'); ?></span>
                            </div>
                        </div>
                    </div>
                        <?php } ?>
                </div>     

                <?php
                    $query = "SELECT * FROM tblemployee";
                    $empResult = $dbh->executeSelect($query);
                    if ($empResult) {
                        foreach ($empResult as $emp) {
                            $employee = new Employee();
                            $employee->setByRow($emp);
                ?>
                <div class="table-row">
                    <div class="table-cell">
                        <div class="cell-content first">
                            <div class="text-contents">
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
                    <div class="table-cell button <?php if ($date == date('Y-m-d')) { echo "today"; }?>">
                        <div class="cell-content more-dropdown">
                            <div class="text-contents responsive"> 
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

                        <div class="more-info-tile">
                            <div class="text-contents index">
                                <span>
                                    <img src="media/img/icons/user.png" clock="Department icon" class="img-small" />
                                    <?php echo $employee->getName()?>
                                </span>
                            </div>
                            <div class="text-contents index">
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
                            <div class="text-contents index">
                                <span><?php echo "Shift " . ($key + 1) . ": "?></span>
                             </div>
                            <?php
                                        include "includes/tile-dropdown.inc.php"; 
                                    }
                                } else{
                                    include "includes/tile-dropdown.inc.php"; 
                                }  
                            ?>             
                            <span class="more-info-button add"> <!-- Book more hours onto tile (anchor point) -->
                                <img src="media/img/icons/plus.png" alt="Add new hours icon" />
                            </span>
                            <span class="more-info-button return">
                                <img src="media/img/icons/arrow.png" alt="Close dropdown icon" />
                            </span>
                        </div>
                    </div>

                        
                    <?php } else { // No results found within employee row (no booked hours) ?>
                    <div class="table-cell button empty <?php if ($date == date('Y-m-d')) { echo "today"; }?>"></div>
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
            
    
            <div class="overview-footer">

            </div>
        </div>

<?php 
    require_once "includes/footer.inc.php";
?>

        