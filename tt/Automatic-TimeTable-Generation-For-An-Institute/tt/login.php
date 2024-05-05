<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php

session_start();

function authenticateUser($userName, $password, $userType) {
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'timetable';

    if ($userType == "Administrator") {
        $dbName = 'timetable';
    } elseif ($userType == "timetableSeeker" || $userType == "Employer") {
        $dbName = 'timetable';
    } else {
        throw new Exception('Invalid user type');
    }

    $db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($db->connect_error) {
        throw new Exception('Database connection error: '. $db->connect_error);
    }

    $stmt = $db->prepare('SELECT * FROM? WHERE UserName =? AND Password =? AND Status =?');
    $stmt->bind_param('ssss', $tableName, $userName, $password, $status);

    if ($userType == "Administrator") {
        $tableName = 'user_master';
        $status = '';
    } elseif ($userType == "JobSeeker") {
        $tableName = 'jobseeker_reg';
        $status = 'Confirm';
    } else {
        $tableName = 'employer_reg';
        $status = 'Confirm';
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $records = $result->num_rows;

    if ($records == 0) {
        echo '<script type="text/javascript">alert("Wrong UserName or Password");window.location=\'index.php\';</script>';
    } else {
        $row = $result->fetch_assoc();
        if ($userType == "JobSeeker") {
            $_SESSION['ID'] = $row['JobSeekId'];
            $_SESSION['Name'] = $row['JobSeekerName'];
            header("location:JobSeeker/index.php");
        } elseif ($userType == "Employer") {
            $_SESSION['ID'] = $row['EmployerId'];
            $_SESSION['Name'] = $row['CompanyName'];
            header("location:Employer/index.php");
        } else {
            header("location:Admin/index.php");
        }
    }

    $stmt->close();
    $db->close();
}

$userName = $_POST['txtUser'];
$password = $_POST['txtPass'];
$userType = $_POST['cmbUser'];

try {
    authenticateUser($userName, $password, $userType);
} catch (Exception $e) {
    echo 'Error: '. $e->getMessage();
}
?>
</body>
</html>
