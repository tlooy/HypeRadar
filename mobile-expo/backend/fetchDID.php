<?php
include('db.php');

$deviceToken	= $decodedData['Token'];
$userId	= $decodedData['UserID'];

//$deviceToken	= "ExponentPushToken[xRVMIeId6t0BQS_eY94El7]";
//$userId	= 19;

$sqlSelect  = 	"SELECT notification_token " .
		"  FROM notification_devices " .
		" WHERE notification_token = ?" .
		"   AND user_id = ?;";

$stmtSelect = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtSelect, $sqlSelect)) {
	$Message = 'Unable to fetch Device ID for this User from the database (Select Prepare)';
	$response[] = array("Message" => $Message);
	echo json_encode($response);
	exit();
}

mysqli_stmt_bind_param($stmtSelect, "si", $deviceToken, $userId);

if(mysqli_stmt_execute($stmtSelect)){
	$result = mysqli_stmt_get_result($stmtSelect);
	if(mysqli_num_rows($result) == 0){
		$Message = 'Not Registered';
		$response[] = array("Message" => $Message);
		echo json_encode($response);
	} else {
		$Message = 'Registered';
		$response[] = array("Message" => $Message);
		echo json_encode($response);
		exit();
	}

} else {
    $Message = 'Unable to fetch Device ID for this User from the database (Select Execute)';
}





