<?php
    session_start();
    $title = "Login";
    $stylesheet = "landing";

    date_default_timezone_set('Europe/London');
    require_once "includes/header.inc.php";
    require_once "includes/dbh.inc.php";
    require_once "includes/classes.php";

    if (Input::exists()) {
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

            if ($user->login(
                $_POST['email'], 
                $_POST['pwd'], 
                $_POST['cuid']
                )) {
                header("Location: app/overview?login=success");
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
        <input type="text" name="email"  placeholder="Email" value="<?php echo escape(Input::get('email')); ?>" />
        <input type="password" name="pwd"  placeholder="Password" />
        <input type="text" name="cuid"  placeholder="Company Unique ID" value="<?php echo escape(Input::get('cuid')); ?>" />

        <input type="hidden" name="login" />

        <button name="submit">Start</button>
    </form>

<?php 
    require_once "includes/footer.inc.php";
?>