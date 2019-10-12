<?php
    session_start();
    $title = "Login";
    $stylesheet = "landing";

    date_default_timezone_set('Europe/London');
    require_once "includes/header.inc.php";
    require_once "includes/dbh.inc.php";
    require_once "includes/classes.php";

    if (Input::exists('submit')) {
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

<?php //include 'includes/landing/banner.php' ?>

<div class="login">
    <div class="login__container">
        <div class="login__logo">
            <img src="media/img/icons/logo-color1.png" alt="Zero hour contractor" />
        </div>
        
        <div class="login__content">
            <form action="" method="POST" autocomplete="off" class="login__form">
                <div class="login__form-input">
                    <label>Email </label>
                    <input type="text" name="email"  placeholder="Email" value="<?php echo escape(Input::get('email')); ?>" />
                </div>
                
                <div class="login__form-input">
                    <label>Password </label>
                    <input type="password" name="pwd"  placeholder="Password" />
                </div>

                <div class="login__form-input">
                    <label>Company Unique ID </label>
                    <input type="text" name="cuid"  placeholder="Company Unique ID" value="<?php echo escape(Input::get('cuid')); ?>" />
                </div>

                <button name="submit">Login</button>
            </form>

            <div class="login__tools">
                <ul>
                    <li><a href="register">Register</a></li>
                    <li><a href="forgotten-password">Forgotten Password?</a></li>
                </ul> 
            </div>
              
            
            <span class="login__return"><a href="index">← Return to Zero Hour Contrastor</a></span>
        </div>
    </div>
</div> 




<?php
    //include "includes/landing/bottom-nav.php";
?>

<?php 
    require_once "includes/footer.inc.php";
?>