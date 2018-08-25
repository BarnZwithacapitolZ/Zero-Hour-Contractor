<?php
    $title = "Zero Hour Contractor";

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
            <div class="about-flex">              
                <div class="about-contents">
                    <div class="about">
                        <img src="media/img/icons/weekly.png" />
                        <h2>Overview Manager</h2>
                        <p>this is some text to test the thing inside of the thing so I know if it works or not</p>
                    </div>

                    <div class="about">
                        <img src="media/img/icons/sigma-white.png" />
                        <h2>Total</h2>
                        <p>this is some text to test the thing inside of the thing so I know if it works or not</p>
                    </div>

                    <div class="about">
                        <img src="media/img/icons/contract.png" />
                        <h2>Contracts</h2>
                        <p>this is some text to test the thing inside of the thing so I know if it works or not</p>
                    </div>

                    <div class="about">
                        <img src="media/img/icons/pound.png" />
                        <h2>Accounts</h2>
                        <p>this is some text to test the thing inside of the thing so I know if it works or not</p>
                    </div>

                    <div class="about">
                        <img src="media/img/icons/wifi.png" />
                        <h2>Header</h2>
                        <p>this is some text to test the thing inside of the thing so I know if it works or not</p>
                    </div>

                    <div class="about">
                        <img src="media/img/icons/bell.png" />
                        <h2>Management</h2>
                        <p>this is some text to test the thing inside of the thing so I know if it works or not</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="test-section">

        </div>


<?php 
    require_once "includes/footer.inc.php";
?>