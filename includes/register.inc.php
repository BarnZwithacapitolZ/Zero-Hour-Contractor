<?php

session_start();

if (isset($_POST['submit'])) {
    require_once "dbh.inc.php";
    $dbh = new Dbh();

    // Company registration
    $company = array(
        'name' => filter_input(INPUT_POST, 'name'),
        'hours' => filter_input(INPUT_POST, 'hours', FILTER_VALIDATE_INT),
        'payout' => filter_input(INPUT_POST, 'payout'),
        'color' => filter_input(INPUT_POST, 'color')
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
            'first' => filter_input(INPUT_POST, 'first'),
            'last' => filter_input(INPUT_POST, 'last'),
            'email' => filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL),
            'payrate' => filter_input(INPUT_POST, 'payrate', FILTER_VALIDATE_FLOAT),
            'pwd' => filter_input(INPUT_POST, 'pwd'),
            'firmPwd' => filter_input(INPUT_POST, 'firmPwd')
        );

        if ($result = $dbh->invalidCheck($admin)) {
            header("Location: ../register?register=$result");
            exit(); 
        } else {
            if (!preg_match("/^[a-zA-Z]*$/", $admin['first']) || !preg_match("/^[a-zA-Z]*$/", $admin['last'])) {
                header("Location: ../register?register=invalid");
                exit(); 
            } else {
                if ($admin['pwd'] !== $admin['firmPwd']) {
                    header("Location: ../register?register=unequal");
                    exit(); 
                } else {
                    $query = strtr("SELECT * FROM tblemployee WHERE EmployeeEmail=':email'",    
                        [":email" => $admin['email']]);
                    $result = $dbh->executeSelect($query);

                    if ($result) { // Email already exists
                        header("Location: ../register?register=usertaken");
                        exit(); 
                    } else {
                        $query = strtr("INSERT INTO tblorganization (OrganizationName,
                            OrganizationMaxHours, OrganizationPayout, OrganizationColor)
                            VALUES (':name', ':hours', ':payout', ':color')", 
                            [
                                ":name" => $company['name'], 
                                ":hours" => $company['hours'], 
                                ":payout" => $company['payout'], 
                                ":color" => $company['color']
                            ]
                        );
                        $dbh->executeQuery($query);

                        $query = strtr("INSERT INTO tblemployee (OrganizationID, EmployeeFirst, 
                            EmployeeLast, EmployeeType, EmployeePayrate, EmployeeEmail, EmployeePassword) 
                            VALUES (':id', ':first', ':last', ':type', ':payrate', ':email', ':pwd')", 
                            [
                                ":id" => $dbh->lastID(), 
                                ":first" => $admin['first'],
                                ":last" => $admin['last'], 
                                ":type" => "admin", 
                                ":payrate" => $admin['payrate'], 
                                ":email" => $admin['email'],
                                ":pwd" => password_hash($admin['firmPwd'], PASSWORD_DEFAULT)
                            ]
                        );
                        $dbh->executeQuery($query);

                        print_r("success!!");
                    }
                }
            }
        }
    }
}