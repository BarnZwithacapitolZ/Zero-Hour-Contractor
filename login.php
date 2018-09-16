<?php
    $title = "Login";
    $stylesheet = "landing";

    date_default_timezone_set('Europe/London');
    require_once "includes/header.inc.php";
    require_once "includes/dbh.inc.php";
    require_once "includes/classes.php";

    if (isset($_POST['login'])) {
        $validate= new Validate();
        $validation = $validate->check($_POST, array(
            'email' => array(
                'required' => true,
                'email' => true
            ),
            'pwd' => array('required' => true),
            'cuid' => array('required' => true)
        ));

        if ($validation->passed()) {
            $user = new Employee();
            $login = $user->login($_POST['email'], $_POST['pwd'], $_POST['cuid']);

            if ($login) {
                echo "success!!!";
            } else {
                echo "login failed you idiot!!!!";
            }
        } else {
            foreach($validation->getErrors() as $error) {
                echo $error, '<br>';
            }
        }
    }
?>

    <form action="" method="POST" autocomplete="off">
        <input type="text" name="email"  placeholder="Email" />
        <input type="password" name="pwd"  placeholder="Password" />
        <input type="text" name="cuid"  placeholder="Company Unique ID" />

        <input type="hidden" name="login" />

        <button name="submit">Start</button>
    </form>

<?php 
    require_once "includes/footer.inc.php";
?>