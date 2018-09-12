<?php
    $title = "Register";
    $stylesheet = "landing";

    date_default_timezone_set('Europe/London');
    require_once "includes/header.inc.php";
    require_once "includes/dbh.inc.php";
    require_once "includes/classes.php";

    $c_name = "";

    if (isset($_GET['submit'])) {
        $c_name = $_GET['company-name'];
    }
?>

    <form action="includes/register.inc.php" method="POST" autocomplete="off">
        <input type="text" name="name"  placeholder="Company Name" value="<?php echo $c_name; ?>" />
        <input type="time" value="08:00" name="start" />
        <input type="time" value="22:00" name="stop" />
        <input type="text" name="hours"  placeholder="Max Hours" />
        <input type="text" name="days"  placeholder="Days Open" />    

        <select name="payout"  placeholder="Payout">
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>

        <!-- maybe add a color or something here ????? -->

        <br /> <br />

        <input type="text" name="first"  placeholder="First Name" />
        <input type="text" name="last"  placeholder="Last Name" />
        <input type="text" name="email"  placeholder="Email Address" />
        <input type="text" name="payrate"  placeholder="Your payrate" />
        <input type="password" name="pwd"  placeholder="Password" />
        <input type="password" name="firmPwd"  placeholder="Confirm Password" />

        <button name="submit">Start</button>
    </form>

<?php 
    require_once "includes/footer.inc.php";
?>