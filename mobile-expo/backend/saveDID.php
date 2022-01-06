<?php
include('db.php');

$deviceToken	= $decodedData['Token'];
$userId	= $decodedData['UserID'];

//$deviceToken	= "dummy";
//$userId	= 19;

$sqlInsert  = "INSERT INTO notification_devices (user_id, notification_token) VALUES (?, ?);";
$stmtInsert = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtInsert, $sqlInsert)) {
	$Message = 'Unable to save Device ID to the database (Insert Prepare)';
	$response[] = array("Message" => $Message);
	echo json_encode($response);
	exit();
}

mysqli_stmt_bind_param($stmtInsert, "is", $userId, $deviceToken);

if(mysqli_stmt_execute($stmtInsert)){
	$Message = 'Success';
}
else {
    $Message = 'Unable to save Device ID to the database (Insert Execute))';
}
$response[] = array("Message" => $Message);
echo json_encode($response);

