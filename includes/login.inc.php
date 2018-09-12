<?php

session_start();

if (isset($_POST['submit'])) {
    require_once "dbh.inc.php";
    $dbh = new Dbh();

    $user = array(
        'u_email' => filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL),
        'u_pwd' => filter_input(INPUT_POST, 'pwd'),
        'u_cuid' => filter_input(INPUT_POST, 'cuid')
    );

    if ($result = $dbh->invalidCheck($user)) {
        header("Location: ../login?login=$result");
        exit();
    } else {
        $query = strtr("SELECT * FROM tblemployee WHERE EmployeeEmail=':email' AND OrganizationID=':cuid'",
            [
                ":email" => $user['u_email'],
                ":cuid" => $user['u_cuid']
            ]
        );
        $result = $dbh->executeSelect($query);

        if (!$result) { // Check for user to exist
            header("Location: ../login?login=nouser");
            exit();
        } else { // If they do exists, check their password is correct
            $row = $result[0];
            $hashedPwdCheck = password_verify($user['u_pwd'], $row['EmployeePassword']);
            if ($hashedPwdCheck == false) {
                header("Location: ../login?login=pwdincorrect");
                exit();
            } elseif ($hashedPwdCheck) {
                $_SESSION['user'] = array(
                    'u_first' => $row['EmployeeFirst'],
                    'u_last' => $row['EmployeeLast'],
                    'u_type' => $row['EmployeeType'],
                    'u_payrate' => $row['EmployeePayrate'],
                    'u_email' => $row['EmployeeEmail'],
                    'u_cuid' => $row['OrganizationID'],
                    'u_id' => $row['EmployeeID']
                );

                $query = strtr("SELECT * FROM tblorganization WHERE OrganizationID=':cuid'",
                    [":cuid" => $user['u_cuid']]
                );
                $result = $dbh->executeSelect($query);
                $row = $result[0];

                $_SESSION['company'] = array(
                    'c_name' => $row['OrganizationName'],
                    'c_start' => $row['OrganizationStart'],
                    'c_stop' => $row['OrganizationStop'],
                    'c_hours' => $row['OrganizationMaxHours'],
                    'c_days' => $row['OrganizationDays'],     
                    'c_payout' => $row['OrganizationPayout']
                );

                header("Location: ../overview?login=success");
                exit(); 
            }
        }  
    } 
} else {
    header("Location: ../login?login=error");
    exit();
}