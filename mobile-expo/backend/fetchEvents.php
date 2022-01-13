<?php
include('db.php');

//$userId	= $decodedData['UserID'];
$userId	= 18;

$sqlSelect = 	"SELECT name" . 
				" FROM events E, subscriptions S, users U " .
				" WHERE U.id = S.user_id " . 
				" AND S.event_id = E.id " .
				" AND S.user_id = ? ";
// TODO: fix exception handling that was originally implemented using "Message" => "Success" (Note line 32 is commented out)
$stmtSelect = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmtSelect, $sqlSelect)) {
	$Message = 'Unable to fetch Events from the database (Select Prepare)';
	$response[] = array("Message" => $Message);
	echo json_encode($response);
	exit();
} 
mysqli_stmt_bind_param($stmtSelect, "i", $userId);

if(mysqli_stmt_execute($stmtSelect)){
	$result = mysqli_stmt_get_result($stmtSelect);
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			$events[] = array('name' => $row['name']);
		}
		$response = array("Events" => $events);
		echo json_encode($response);
		exit();
	}
} else {
	$response[] = array("Message" => 'Unable to fetch Events from the database (Select Execute.');
	echo json_encode($response);
	exit();
}
