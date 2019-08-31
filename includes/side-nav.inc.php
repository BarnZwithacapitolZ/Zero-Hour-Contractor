<div id="side-nav" class="side-nav__container">
    <!-- Main content panel for whole application -->
    <nav class="side-nav__nav">
        <ul>
            <?php 
                // Make sure to echo employee name once login is set up (as a session variable)
                $icons = array(
                    array("profile.jpg", $employee->getFullName($user), "accounts"), 
                    array("weekly.png", "Weekly Overview", "overview"), 
                    array("daily.png", "Daily Overview", "###"), 
                    array("bell.png", "Notifications", "###"), 
                    array("pound.png", "Accounts", "accounts"),
                    array("user-white.png", "Employees", "employee"),
                    array("department-white.png", "Departments", 'department'),
                    array("settings.png", "Settings", "settings")
                );               
            ?>  


            <li class="nav__icon nav__icon--link" data-tool-tip="<?php echo $icons[0][1]; ?>"> 
                <a href="<?php echo $icons[0][2]; ?>"><img src="../media/img/icons/<?php echo $icons[0][0]; ?>" /></a>
                <!--<div class="notification-bubble">!</div>-->
            </li>

            <li class="nav__icon nav__icon--link" data-tool-tip="<?php echo $icons[1][1]; ?>"> 
                <a href="<?php echo $icons[1][2]; ?>"><img src="../media/img/icons/<?php echo $icons[1][0]; ?>" /></a>
                <!--<div class="notification-bubble">!</div>-->
            </li>

            <!-- <li class="nav__icon nav__icon--link" data-tool-tip="<?php echo $icons[3][1]; ?>"> 
                <a href="<?php echo $icons[3][2]; ?>"><img src="../media/img/icons/<?php echo $icons[3][0]; ?>" /></a>
                <div class="notification-bubble">!</div>
            </li> -->

            <li class="nav__icon nav__icon--link" data-tool-tip="<?php echo $icons[4][1]; ?>"> 
                <a href="<?php echo $icons[4][2]; ?>"><img src="../media/img/icons/<?php echo $icons[4][0]; ?>" /></a>
                <!--<div class="notification-bubble">!</div>-->
            </li>

            <?php                 
                if ($user->EmployeeType == "admin") {
            ?>
            <li class="nav__icon nav__icon--link" data-tool-tip="<?php echo $icons[5][1]; ?>"> 
                <a href="<?php echo $icons[5][2]; ?>"><img src="../media/img/icons/<?php echo $icons[5][0]; ?>" /></a>
                <!--<div class="notification-bubble">!</div>-->
            </li>

            <li class="nav__icon nav__icon--link" data-tool-tip="<?php echo $icons[6][1]; ?>"> 
                <a href="<?php echo $icons[6][2]; ?>"><img src="../media/img/icons/<?php echo $icons[6][0]; ?>" /></a>
                <!--<div class="notification-bubble">!</div>-->
            </li>

            <?php } ?>

            <li class="nav__icon nav__icon--link nav__icon--last" data-tool-tip="<?php echo $icons[7][1]; ?>"> 
                <a href="<?php echo $icons[7][2]; ?>"><img src="../media/img/icons/<?php echo $icons[7][0]; ?>" /></a>
                <!--<div class="notification-bubble">!</div>-->
            </li>
        </ul>
    </nav>
</div>