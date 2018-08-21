<?php
    require_once "includes/header.inc.php";
    require_once "includes/dbh.inc.php";
    require_once "includes/classes.php";

    $dbh = new Dbh();

    $week = "last";
?>

        <header>
            <div class="header-contents">
                <span><</span><span>This Week</span><span>></span>
            </div>
        </header>

        <div id="side-navigation">
            <!-- Main content panel for whole application -->
            <ul class="icon-list">
                <?php 
                    $icons = array("profile.jpg", "weekly.png", "daily.png", "bell.png", "pound.png");
                    foreach ($icons as $icon):
                ?>  

                <li class="icon link"> 
                    <img src="media/img/<?php echo $icon; ?>" />
                    <!--<div class="notification-bubble">!</div>-->
                </li>

                <?php endforeach; ?>
            </ul>
        </div>

        <div id="overview-manager">
            <div class="table-overview">
                <div class="table-row row-header">
                    <div class="table-cell table-header">
                        <div class="cell-content"><div class="text-contents"><span>Name</span></div></div>
                    </div>

                    <?php
                        $days = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
                        for ($i = 0; $i < count($days); $i++):        
                            $date = date('Y-m-d', strtotime('monday ' . $week . ' week') + (($i + 1) * 86300));                   
                    ?>
                    <div class="table-cell table-header <?php if ($date == date('Y-m-d')) { echo "today"; }?>">
                        <div class="cell-content">
                            <div class="text-contents">
                                <span><?php echo $days[$i]; ?></span>
                                <span class="day"><?php echo date('d', strtotime('monday ' . $week . ' week')) + $i; ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>     

                <?php
                    $query = "SELECT * FROM tblemployee";
                    $empResult = $dbh->executeSelect($query);
                    if ($empResult):
                        foreach ($empResult as $emp):
                            $employee = new Employee();
                            $employee->setByRow($emp);
                ?>
                <div class="table-row">
                    <div class="table-cell">
                        <div class="cell-content first">
                            <div class="text-contents">
                                <img src="media/img/profile.jpg" class="user-pic" />
                                <span><?php echo $employee->getName(); ?></span>
                            </div>
                        </div>
                    </div>

                    <?php                                   
                            for ($i = 1; $i < count($days) + 1; $i++):
                                $select = $employee->getID();
                                $date = date('Y-m-d', strtotime('monday ' . $week . ' week') + ($i * 86300)); // 86300 for new day
                                
                                //if ($date == date('Y-m-d')){
                                //    print_r("today");
                                //}

                                $query = "SELECT * FROM tblbook WHERE BookDate='$date' AND EmployeeID='$select' ORDER BY StartTime, EndTime";
                                $bookResult = $dbh->executeSelect($query);
                                if ($bookResult):
                                    $bookedHours = new HourTile();
                                    $bookedHours->setByRow($bookResult[0]); // Only show the first result of any day
                    ?>
                    <div class="table-cell button <?php if ($date == date('Y-m-d')) { echo "today"; }?>">
                        <div class="cell-content <?php if (count($bookResult) > 1) { echo "more-lrg";} else { echo "more-sml"; }?>">
                            <div class="text-contents responsive"> 
                                <span>
                                    <img src="media/img/clock.png" alt="Clock time icon" class="img-small" />
                                    <?php echo $bookedHours->getStart(); ?> - <?php echo $bookedHours->getEnd(); ?>
                                </span>
                                <span>
                                    <img src="media/img/department.png" clock="Department icon" class="img-small" />
                                    <?php echo $bookedHours->getDepartment(); ?>
                                </span>
                            </div>
                        </div>

                        
                        <?php if (count($bookResult) <= 1): ?>
                        <div class="more-info-tile sml">
                            <div class="text-contents index">
                                <span><?php echo "(" . $bookedHours->getDate() . ")"?></span>
                            </div>
                            
                            <?php include "includes/tile-dropdown.inc.php"; ?>

                            <span class="more-info-button add"> <!-- Book more hours onto tile (anchor point) -->
                                <img src="media/img/plus.png" alt="Add new hours icon" />
                            </span>
                            <span class="more-info-button return">
                                <img src="media/img/arrow.png" alt="Close dropdown icon" />
                            </span>
                        </div>


                        <?php elseif (count($bookResult) > 1): ?>
                        <div class="notification-bubble">+<?php echo count($bookResult) - 1;?></div>
                        <div class="more-info-tile lrg">
                            <?php
                                foreach ($bookResult as $key => $book): // Only loop through if there is more than 1
                                    $bookedHours->setByRow($book);
                            ?>
                            <div class="text-contents index">
                                <span><?php echo $key + 1 . ": (" . $bookedHours->getDate() . ")"?></span>
                            </div>
                            <?php
                                include "includes/tile-dropdown.inc.php";
                                endforeach;
                            ?>
                            <span class="more-info-button add"> <!-- Book more hours onto tile (anchor point) -->
                                <img src="media/img/plus.png" alt="Add new hours icon" />
                            </span>
                            <span class="more-info-button return">
                                <img src="media/img/arrow.png" alt="Close dropdown icon" />
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>   
                    
                    
                    <?php else: // No results found within employee row (no booked hours) ?>
                    <div class="table-cell button empty <?php if ($date == date('Y-m-d')) { echo "today"; }?>"></div>
                    <?php
                                endif;
                            endfor;
                    ?>
                </div>

                <?php
                        endforeach;
                    else: // No results found for employees (no employees)
                        echo "there is nothing to display here!";
                    endif;
                ?>
            </div>  
            
    
            <div class="overview-footer">

            </div>
        </div>

<?php 
    require_once "includes/footer.inc.php";
?>

        