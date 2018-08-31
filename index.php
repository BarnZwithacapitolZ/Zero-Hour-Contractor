<?php
    $title = "Zhc | Gain Contract Control";

    date_default_timezone_set('Europe/London');
    require_once "includes/header.inc.php";
    require_once "includes/dbh.inc.php";
    require_once "includes/classes.php";
?>

        <header id="header__landing-header">
            <nav class="header__nav">
                <ul>    
                    <li><a href="index"><img src="media/img/icons/logo-color1.png" alt="Zero hour contractor" /></a></li>
                    <li><a class="nav__link" href="overview">How it works</a></li>
                    <li><a class="nav__link" href="overview">Register</a></li>
                    <li><a class="nav__login" href="overview">Login</a></li>
                    <li class="nav__toggle"><span></span></li>
                </ul>              
            </nav>  
            
            <nav class="header__nav-dropdown">
                <ul>           
                    <li><a class="nav__link" href="overview">How it works</a></li>
                    <li><a class="nav__link" href="overview">Register</a></li>
                    <li><a class="nav__login" href="overview">Login</a></li>
                </ul>
            </nav>
        </header>


        <div id="hl-banner" class="hl-banner__container">
            <div class="hl-banner__header">
                <h1 class="hl-banner__headline">Manage hours, accounts and employees all in one place.</h1>
                <p class="hl-banner__subheading">Register your business for free and start managing your contracts today.</p>

                <form action="overview" class="hl-banner__company-register" autocomplete="off">
                    <span error-output="You must enter a name!">
                        <input class="company-register__input" type="text" name="company-name"  placeholder="Business name" />
                    </span>
                    <button class="company-register__submit">Start</button>
                </form>
            </div>
        </div>

        <div id="hl-about" class="hl-about__container">    
            <div class="hl-about__row">
                <div class="hl-about__col">
                    <img src="media/img/icons/weekly.png" alt="Overview Manager icon" />
                    <h2 class="hl-about__headline">Overview Manager</h2>
                    <p class="hl-about__desc">Create a virtual timetable to see when, where and who you are working with.</p>
                </div>

                <div class="hl-about__col">
                    <img src="media/img/icons/sigma-white.png" alt="Totals icon" />
                    <h2 class="hl-about__headline">Total</h2>
                    <p class="hl-about__desc">Manage the hourly totals of each employee; daily, weekly or monthly.</p>
                </div>

                <div class="hl-about__col">
                    <img src="media/img/icons/contract.png" alt="Contracts icon" />
                    <h2 class="hl-about__headline">Contracts</h2>
                    <p class="hl-about__desc">Set automatic timestamps to be set each week, month or year; know what hours are avaialble each day, when and where.</p>
                </div>

                <div class="hl-about__col">
                    <img src="media/img/icons/pound.png" alt="Accounts icon" />
                    <h2 class="hl-about__headline">Accounts</h2>
                    <p class="hl-about__desc">Automatically calculate the payrole for each employee: monthly, weekly or yearly; set start and cut-off points for each month.</p>
                </div>

                <div class="hl-about__col">
                    <img src="media/img/icons/wifi.png" alt="Connect icon" />
                    <h2 class="hl-about__headline">Connect</h2>
                    <p class="hl-about__desc">View your hours from anywhere at anytime; simply log into your account connected to an organisation.</p>
                </div>

                <div class="hl-about__col">
                    <img src="media/img/icons/bell.png" alt="Management icon" />
                    <h2 class="hl-about__headline">Management</h2>
                    <p class="hl-about__desc">Simplify the tracking of employee's data; let us do it for you, we won't let you down!</p>
                </div>
            </div>              
        </div>

        <div id="hl-target" class="hl-target__container">
            <div class="hl-target__col">
                <div class="hl-target__small-business-icon">
                    <img src="media/img/icons/small-buisiness.png" alt="Small Business icon" />
                </div>
            </div>

            <div class="hl-target__col">
                <div class="hl-target__header">
                    <h1 class="hl-target__headline">Simplify</h1>
                    <p class="hl-target__desc">We designed Zero Hour Contractor for small businesses looking for a solution to the hassle of managing zero-hour-contracts.</p>
                    <p class="hl-target__desc"> 
                        Be it employees not knowing when they are working, or managers not knowing who is covering;
                        our solution makes it easy to always keep track of contracts, as well as providing the useful analytics 
                        to make the next bookings easier.
                    </p>
                </div>
            </div>
        </div>

        <div id="hl-more" class="hl-more__container">
            <p class="hl-more__desc">Eager to learn more huh?</p>
            <a class="hl-more__link" href="#">How it works</a>
        </div>

        <footer class="footer__landing-footer">
            <div class="footer__col">
                <nav class="footer__nav">
                    <ul>
                        <li><a href="overview">Register</a></li>
                        <li><a href="overview">Login</a></li>
                        <li><a href="index">Home</a></li>
                        <li><a href="#">How it works</a></li>
                    </ul>
                </nav>
            </div>
            <div class="footer__col">
                <nav class="footer__nav">
                    <ul>
                        <li>Zero Hour Contractor</li>
                        <li>Copyright &copy; 2018</li>
                        <li><a href="#">Terms</a></li>
                    </ul>
                </nav>
            </div>
        </footer>


<?php 
    require_once "includes/footer.inc.php";
?>