<?php
    $title = "Login";
    $stylesheet = "landing";

    date_default_timezone_set('Europe/London');
    require_once "includes/header.inc.php";
    require_once "includes/dbh.inc.php";
    require_once "includes/classes.php";
?>

    <form action="includes/login.inc.php" method="POST" autocomplete="off">
        <input type="text" name="email"  placeholder="Email" />
        <input type="password" name="pwd"  placeholder="Password" />
        <input type="text" name="cuid"  placeholder="Company Unique ID" />

        <button name="submit">Start</button>
    </form>

<?php 
    require_once "includes/footer.inc.php";
?>