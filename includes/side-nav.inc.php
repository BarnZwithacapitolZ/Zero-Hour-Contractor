<div id="side-navigation">
    <!-- Main content panel for whole application -->
    <ul class="icon-list">
        <?php 
            // Make sure to echo employee name once login is set up (as a session variable)
            $icons = array(array("profile.jpg", "Sam Barnes"), array("weekly.png", "Weekly Overview"), 
                array("daily.png", "Daily Overview"), array("bell.png", "Notifications"), array("pound.png", "Accounts"));
            foreach ($icons as $icon) {
        ?>  
        <li class="icon link" data-tool-tip="<?php echo $icon[1]; ?>"> 
            <img src="media/img/icons/<?php echo $icon[0]; ?>" />
            <!--<div class="notification-bubble">!</div>-->
        </li>
        <?php } ?>
        <li class="icon link last" data-tool-tip="Settings"> 
            <img src="media/img/icons/settings.png" />
            <!--<div class="notification-bubble">!</div>-->
        </li>
    </ul>
</div>