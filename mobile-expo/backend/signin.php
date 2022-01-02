<?php
include('db.php');

$usernameoremail = $decodedData['Email'];
$userpwd         = ($decodedData['Password']); 

$sql  = "SELECT * FROM users WHERE userid = ? OR useremail = ?;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
	exit();
}

mysqli_stmt_bind_param($stmt, "ss", $usernameoremail, $usernameoremail);
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($results, MYSQLI_ASSOC);

if (count($rows) > 0) {
	$row = $rows[0];
	$pwdhashed = $row["userpwd"];

	$checkPwd = password_verify($userpwd, $pwdhashed);

	if ($checkPwd === false) {
		$Message = 'Invalid username-email or password (1)';
		$response[] = array("Message" => $Message);
		echo json_encode($response);
		exit();
	}
//	elseif  ($checkPwd === true) {
	else {
		$Message = 'Success';
		$response[] = array("Message" => $Message);
		$response[] = array("UserID"  => $row["id"]);
		echo json_encode($response);
		exit();
	}
}
else {
	$Message = 'Invalid username/email or password (2)';
	$response[] = array("Message" => $Message);
	echo json_encode($response);
	exit();
}

