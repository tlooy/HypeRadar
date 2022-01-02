<?php
include('db.php');

$deviceToken	= $decodedData['Token'];
$userId	= $decodedData['UserID'];

$sql  = "INSERT INTO notification_devices (user_id, notification_token) VALUES (?, ?);";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
	$Message = 'Unable to save Device ID to the database (1)';
	$response[] = array("Message" => $Message);
	echo json_encode($response);
	exit();
}

mysqli_stmt_bind_param($stmt, "is", $userId, $deviceToken);

if(mysqli_stmt_execute($stmt)){
	$Message = 'Success';
}
else {
    $Message = 'Unable to save Device ID to the database (2)';
}
$response[] = array("Message" => $Message);
echo json_encode($response);

