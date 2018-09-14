<div id="side-nav" class="side-nav__container">
    <!-- Main content panel for whole application -->
    <nav class="side-nav__nav">
        <ul>
            <?php 
                // Make sure to echo employee name once login is set up (as a session variable)
                $icons = array(array("profile.jpg", $user->getName("full")), array("weekly.png", "Weekly Overview"), 
                    array("daily.png", "Daily Overview"), array("bell.png", "Notifications"), array("pound.png", "Accounts"));
                foreach ($icons as $icon) {
            ?>  
            <li class="nav__icon nav__icon--link" data-tool-tip="<?php echo $icon[1]; ?>"> 
                <img src="media/img/icons/<?php echo $icon[0]; ?>" />
                <!--<div class="notification-bubble">!</div>-->
            </li>
            <?php 
                } 
                if ($user->getType() == "admin") {
            ?>
            <li class="nav__icon nav__icon--link" data-tool-tip="Add New..."> 
                <img src="media/img/icons/plus.png" />
                <!--<div class="notification-bubble">!</div>-->
            </li>
            <?php } ?>
            <li class="nav__icon nav__icon--link nav__icon--last" data-tool-tip="Settings"> 
                <img src="media/img/icons/settings.png" />
                <!--<div class="notification-bubble">!</div>-->
            </li>
        </ul>
    </nav>
</div>