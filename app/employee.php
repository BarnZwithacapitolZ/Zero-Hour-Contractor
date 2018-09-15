<?php
    session_start();
    $title = "Weekly Overview";
    $stylesheet = "main";

    date_default_timezone_set('Europe/London');
    require_once "../includes/header.inc.php";
    require_once "../includes/dbh.inc.php";
    require_once "../includes/classes.php";

    if (isset($_SESSION['user']) && isset($_SESSION['company'])) {
        $user = new Employee();
        $user->setByArray($_SESSION['user']);

        $company = new Company();
        $company->setByArray($_SESSION['company']);

        if (isset($_POST['entry'])) {
            $new = new Employee();
    
            $entry = array(
                'u_first' => filter_input(INPUT_POST,'first'),
                'u_last' => filter_input(INPUT_POST, 'last'),
                'u_type' => "employee",         
                'u_payrate' => filter_input(INPUT_POST, 'payrate', FILTER_VALIDATE_FLOAT),
                'u_email' => filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL),
                'u_pwd' => filter_input(INPUT_POST, 'pwd'),
                'u_firmPwd' => filter_input(INPUT_POST, 'firmPwd'),
                'u_cuid' => $company->getId()
             );
            $new->setByPost($entry);
            $new->insertEntry($entry['u_firmPwd']);
        }
    } else {
        header("Location: /zero-hour-contractor/index?login=nologin");
        exit();
    }
?>

        <div id="overview-manager" class="overview-manager__container">
            <form action="employee" method="POST" autocomplete="off">
                <input type="text" name="first"  placeholder="First Name" />
                <input type="text" name="last"  placeholder="Last Name" />
                <input type="text" name="email"  placeholder="Email Address" />
                <input type="text" name="payrate"  placeholder="Your payrate" />
                <input type="password" name="pwd"  placeholder="Password" />
                <input type="password" name="firmPwd"  placeholder="Confirm Password" />

                <input type="hidden" name="entry" />

                <button name="submit">Start</button>
            </form>
        </div>

        <?php require_once "../includes/side-nav.inc.php" ?>


<?php 
    require_once "../includes/footer.inc.php";
?>