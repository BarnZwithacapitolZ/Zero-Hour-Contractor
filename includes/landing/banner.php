<?php
    $hrefs = array(
        'home' => "index",
        'overview' => "apps/overview",
        'register' => "register",
        'login' => "login",
        'works' => "###",
        'terms' => "###"
    );
?>

<header id="header__landing-header">
    <nav class="landing-header__nav">
        <ul>    
            <li><a href="<?php echo $hrefs['home']; ?>"><img src="media/img/icons/logo-color1.png" alt="Zero hour contractor" /></a></li>
            <li><a class="nav__link" href="<?php echo $hrefs['works']; ?>">How it works</a></li>
            <li><a class="nav__link" href="<?php echo $hrefs['register']; ?>">Register</a></li>
            <li><a class="nav__login" href="<?php echo $hrefs['login']; ?>">Login</a></li>
            <li class="nav__toggle"><span></span></li>
        </ul>              
    </nav>  
    
    <nav class="landing-header__nav-dropdown">
        <ul>           
            <li><a class="nav__link" href="<?php echo $hrefs['works']; ?>">How it works</a></li>
            <li><a class="nav__link" href="<?php echo $hrefs['register']; ?>">Register</a></li>
            <li><a class="nav__login" href="<?php echo $hrefs['login']; ?>">Login</a></li>
        </ul>
    </nav>
</header>