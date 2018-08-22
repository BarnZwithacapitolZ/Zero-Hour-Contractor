<div id="side-navigation">
    <!-- Main content panel for whole application -->
    <ul class="icon-list">
        <?php 
            $icons = array("profile.jpg", "weekly.png", "daily.png", "bell.png", "pound.png");
            foreach ($icons as $icon) {
        ?>  

        <li class="icon link"> 
            <img src="media/img/<?php echo $icon; ?>" />
            <!--<div class="notification-bubble">!</div>-->
        </li>

        <?php } ?>
    </ul>
</div>