<?php
    $title = "Zhc | Gain Contract Control";

    date_default_timezone_set('Europe/London');
    require_once "includes/header.inc.php";
    require_once "includes/dbh.inc.php";
    require_once "includes/classes.php";
?>

        <header class="landing-header">
            <div class="header-contents">
                <ul>    
                    <li><a href="index"><img src="media/img/icons/logo-color1.png" alt="Zero hour contractor" /></a></li>
                    <li><a href="overview">How it works</a></li>
                    <li><a href="overview">Register</a></li>
                    <li><a href="overview">Login</a></li>
                </ul>
            </div>   
        </header>


        <div id="main-banner">
            <div class="banner-contents">
                <h1>Manage hours, accounts and employees all in one place.</h1>
                <p>Register your business for free and start managing your contracts today.</p>

                <form action="overview" class="company-register">
                    <input type="text" name="company-name"  placeholder="Business name" />
                    <button>Start</button>
                </form>
            </div>
        </div>

        <div id="about-display">    
            <div class="about-contents">
                <div class="about">
                    <img src="media/img/icons/weekly.png" />
                    <h2>Overview Manager</h2>
                    <p>Create a virtual timetable to see when, where and who you are working with.</p>
                </div>

                <div class="about">
                    <img src="media/img/icons/sigma-white.png" />
                    <h2>Total</h2>
                    <p>Manage the hourly totals of each employee; daily, weekly or monthly.</p>
                </div>

                <div class="about">
                    <img src="media/img/icons/contract.png" />
                    <h2>Contracts</h2>
                    <p>Set automatic timestamps to be set each week, month or year; know what hours are avaialble each day, when and where.</p>
                </div>

                <div class="about">
                    <img src="media/img/icons/pound.png" />
                    <h2>Accounts</h2>
                    <p>Automatically calculate the payrole for each employee: monthly, weekly or yearly; set start and cut-off points for each month.</p>
                </div>

                <div class="about">
                    <img src="media/img/icons/wifi.png" />
                    <h2>Connect</h2>
                    <p>View your hours from anywhere at anytime; simply log into your account connected to an organisation.</p>
                </div>

                <div class="about">
                    <img src="media/img/icons/bell.png" />
                    <h2>Management</h2>
                    <p>Simplify the tracking of employee's data; let us do it for you, we won't let you down!</p>
                </div>
            </div>              
        </div>

        <div class="target-display">
            <div class="split-info">
                <div class="dep-img">
                    <img src="media/img/icons/small-buisiness.png" />
                </div>
            </div>

            <div class="split-info">
                <div class="target">
                    <h1>Simplify</h1>
                    <p>We designed Zero Hour Contractor for small businesses looking for a solution to the hassle of managing zero-hour-contracts.</p>
                    <p>
                        Be it employees not knowing when they are working, or managers not knowing who is covering;
                        our solution makes it easy to always keep track of contracts, as well as providing the useful analytics 
                        to make the next bookings easier.
                    </p>
                </div>
            </div>
        </div>

        <div class="learn-more">
            <p>Eager to learn more huh?</p>
            <a href="#">How it works</a>
        </div>

        <footer class="end">
            <div class="col">
                <ul>
                    <li><a href="overview">Register</a></li>
                    <li><a href="overview">Login</a></li>
                    <li><a href="index">Home</a></li>
                    <li><a href="#">How it works</a></li>
                </ul>
            </div>
            <div class="col">
                <ul>
                    <li>Zero Hour Contractor</li>
                    <li>Copyright &copy; 2018</li>
                    <li><a href="#">Terms</a></li>
                </ul>
            </div>
        </footer>


<?php 
    require_once "includes/footer.inc.php";
?>