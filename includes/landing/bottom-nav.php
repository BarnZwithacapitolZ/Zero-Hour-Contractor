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

<footer class="footer__landing-footer">
    <div class="footer__col">
        <nav class="footer__nav">
            <ul>
                <li><a href="<?php echo $hrefs['register']; ?>">Register</a></li>
                <li><a href="<?php echo $hrefs['login']; ?>">Login</a></li>
                <li><a href="<?php echo $hrefs['home']; ?>">Home</a></li>
                <li><a href="<?php echo $hrefs['works']; ?>">How it works</a></li>
            </ul>
        </nav>
    </div>
    <div class="footer__col">
        <nav class="footer__nav">
            <ul>
                <li>Zero Hour Contractor</li>
                <li>Copyright &copy; 2018</li>
                <li><a href="<?php echo $hrefs['terms']; ?>">Terms</a></li>
            </ul>
        </nav>
    </div>
</footer>