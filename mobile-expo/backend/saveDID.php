<?php
include('db.php');

$deviceToken	= $decodedData['Token'];
$userId	= $decodedData['UserID'];

$sqlSelect  = "SELECT notification_token FROM notification_devices WHERE notification_token = ?;";
$stmtSelect = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmtSelect, $sqlSelect)) {
	$Message = 'Unable to save Device ID to the database (Select Prepare)';
	$response[] = array("Message" => $Message);
	echo json_encode($response);
	exit();
}

mysqli_stmt_bind_param($stmtSelect, "s", $deviceToken);

if(mysqli_stmt_execute($stmtSelect)){
	$result = mysqli_stmt_get_result($stmtSelect);
	if(mysqli_num_rows($result) == 0){
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
	} else {
		$Message = 'This device already has a device token in HypeRadar';
		$response[] = array("Message" => $Message);
		echo json_encode($response);
		exit();
	}

} else {
    $Message = 'Unable to save Device ID to the database (1)';
}





