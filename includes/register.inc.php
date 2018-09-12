<?php

session_start();

if (isset($_POST['submit'])) {
    require_once "dbh.inc.php";
    $dbh = new Dbh();

    // Company registration
    $company = array(
        'c_name' => filter_input(INPUT_POST, 'name'),
        'c_start' => filter_input(INPUT_POST, 'start'),
        'c_stop' => filter_input(INPUT_POST, 'stop'),
        'c_hours' => filter_input(INPUT_POST, 'hours', FILTER_VALIDATE_INT),
        'c_days' => filter_input(INPUT_POST, 'days', FILTER_VALIDATE_INT),     
        'c_payout' => filter_input(INPUT_POST, 'payout')
    );
    $companyCheck = false;

    if ($result = $dbh->invalidCheck($company)) {
        header("Location: ../register?register=$result");
		exit(); 
    } else {
        $companyCheck = true;
    }

    // Admin registration in reference to the company
    if ($companyCheck) {
        $admin = array(
            'u_first' => filter_input(INPUT_POST, 'first'),
            'u_last' => filter_input(INPUT_POST, 'last'),
            'u_type' => "admin",         
            'u_payrate' => filter_input(INPUT_POST, 'payrate', FILTER_VALIDATE_FLOAT),
            'u_email' => filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL),
            'u_pwd' => filter_input(INPUT_POST, 'pwd'),
            'u_firmPwd' => filter_input(INPUT_POST, 'firmPwd')
        );

        if ($result = $dbh->invalidCheck($admin)) {
            header("Location: ../register?register=$result");
            exit(); 
        } else {
            if (!preg_match("/^[a-zA-Z]*$/", $admin['u_first']) || !preg_match("/^[a-zA-Z]*$/", $admin['u_last'])) {
                header("Location: ../register?register=invalid");
                exit(); 
            } else {
                if ($admin['u_pwd'] !== $admin['u_firmPwd']) {
                    header("Location: ../register?register=unequal");
                    exit(); 
                } else {
                    $query = strtr("SELECT EmployeeEmail FROM tblemployee WHERE EmployeeEmail=':email'",    
                        [":email" => $admin['u_email']]
                    );
                    $result = $dbh->executeSelect($query);

                    if ($result) { // Email already exists
                        header("Location: ../register?register=usertaken");
                        exit(); 
                    } else {
                        $query = strtr("INSERT INTO tblorganization (OrganizationName, OrganizationStart,
                        OrganizationStop, OrganizationMaxHours, OrganizationDays, OrganizationPayout) 
                        VALUES (':name', ':start', ':stop', ':hours', ':days', ':payout')", 
                            [
                                ":name" => $company['c_name'], 
                                ":start" => $company['c_start'],
                                ":stop" => $company['c_stop'],
                                ":hours" => $company['c_hours'], 
                                ":days" => $company['c_days'],                      
                                ":payout" => $company['c_payout']
                            ]
                        );
                        $dbh->executeQuery($query);
                        $admin['u_cuid'] = $dbh->lastID();

                        $query = strtr("INSERT INTO tblemployee (OrganizationID, EmployeeFirst, 
                            EmployeeLast, EmployeeType, EmployeePayrate, EmployeeEmail, EmployeePassword) 
                            VALUES (':cuid', ':first', ':last', ':type', ':payrate', ':email', ':pwd')", 
                            [
                                ":cuid" => $admin['u_cuid'], 
                                ":first" => $admin['u_first'],
                                ":last" => $admin['u_last'], 
                                ":type" => $admin['u_type'], 
                                ":payrate" => $admin['u_payrate'], 
                                ":email" => $admin['u_email'],
                                ":pwd" => password_hash($admin['u_firmPwd'], PASSWORD_DEFAULT)
                            ]
                        );
                        $dbh->executeQuery($query);
                        $admin['u_id'] = $dbh->lastID();

                        $_SESSION['company'] = $company;
                        $_SESSION['user'] = $admin; // Set the session
                        header("Location: ../overview?login=success");                      
                        exit(); 
                    }
                }
            }
        }
    }
} else {
    header("Location: ../register?register=error");
    exit();
}