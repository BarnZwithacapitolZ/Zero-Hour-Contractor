<?php

session_start();

if (isset($_POST['submit'])) {
    require_once "dbh.inc.php";
    $dbh = new Dbh();

    // Using PDO we don't need mysqli_real_escape_string
    $employeeEmail = $_POST['email'];
    $employeePwd = $_POST['pwd'];
    $companyID = $_POST['cuid'];

    //Pseudo

    // Complete error handlers: empty, no results, incorrect results etc.
    // Use header to direct the player to the overview manager

    if (empty($employeeEmail) || empty($employeePwd) || empty($companyID)) {
        header("Location: ../login?login=empty");
        exit();
    } else {
        $query = "SELECT * FROM tblemployee WHERE EmployeeEmail='$employeeEmail' AND OrganizationID='$companyID'";
        $result = $dbh->executeSelect($query);
        if (!$result) {
            header("Location: ../login?login=nouser");
            exit();
        } else {
            $row = $result[0];
            $hashedPwdCheck = password_verify($employeePwd, $row['EmployeePassword']);

            if ($employeePwd != $row['EmployeePassword']) {
                header("Location: ../login?login=pwdIncorrect");
                exit();
            } else {
                $_SESSION['u_id'] = $row['EmployeeID'];
                $_SESSION['u_cuid'] = $row['OrganizationID'];
                $_SESSION['u_first'] = $row['EmployeeFirst'];
                $_SESSION['u_last'] = $row['EmployeeLast'];
                $_SESSION['u_type'] = $row['EmployeeType'];
                $_SESSION['u_payrate'] = $row['EmployeePayrate'];
                $_SESSION['u_email'] = $row['EmployeeEmail'];
                header("Location: ../overview?login=success");
                exit(); 
            }

            //if ($hashedPwdCheck == false) {
                //header("Location: ../login?login=error");
                //exit();
            //} elseif ($hashedPwd) {
                
            //}
        }  
    } 
}