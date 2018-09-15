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
        $query = strtr(
            "SELECT * 
            FROM tblemployee 
            WHERE EmployeeEmail=':email'
            AND CompanyID=':cuid'",
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
                    'u_cuid' => $row['CompanyID'],
                    'u_id' => $row['EmployeeID']
                );

                $query = strtr(
                    "SELECT * 
                    FROM tblcompany 
                    WHERE CompanyID=':cuid'",
                    [":cuid" => $user['u_cuid']]
                );
                $result = $dbh->executeSelect($query);
                $row = $result[0];

                $_SESSION['company'] = array(
                    'c_name' => $row['CompanyName'],
                    'c_start' => $row['CompanyStart'],
                    'c_stop' => $row['CompanyStop'],
                    'c_hours' => $row['CompanyMaxHours'],
                    'c_startDay' => $row['CompanyStartDay'],     
                    'c_endDay' => $row['CompanyEndDay'],
                    'c_payout' => $row['CompanyPayout'],
                    'c_id' => $row['CompanyID']
                );

                header("Location: ../app/overview?login=success");
                exit(); 
            }
        }  
    } 
} else {
    header("Location: ../login?login=error");
    exit();
}