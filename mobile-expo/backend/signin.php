<?php
include('db.php');

$usernameoremail = $decodedData['Email'];
$userpwd         = ($decodedData['Password']); //password is hashed

$sql  = "SELECT * FROM users WHERE userid = ? OR useremail = ?;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
//    header("location: ./signin.php?errorlogin");
echo "Why is this failing?";
    exit();
}

mysqli_stmt_bind_param($stmt, "ss", $usernameoremail, $usernameoremail);
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($results, MYSQLI_ASSOC);
$row = $rows[0];
if (count($row) > 0) {
    $pwdhashed = $row["userpwd"];

    $checkPwd = password_verify($userpwd, $pwdhashed);

    if ($checkPwd === false) {
//        header("location: ./login.php?errorlogin");
//        exit();     
    $Message = 'Invalid username/email or password (1)';
    }
    elseif  ($checkPwd === true) {
//        session_start();
$Message = 'Success';
//        $_SESSION["id"]         = $row["id"];
//        $_SESSION["userid"]     = $row["userid"];
//        $_SESSION["username"]   = $row["username"];
//        $_SESSION["userrole"]   = $row["userrole"];
//        header("location: ./index.php");
//        exit();     
    }
}
else {
    $Message = 'Invalid username/email or password (2)';
}
$response[] = array("Message" => $Message);
$response[] = array("UserID"  => $row["id"]);
echo json_encode($response);
