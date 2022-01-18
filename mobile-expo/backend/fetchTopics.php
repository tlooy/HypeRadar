<?php
include('db.php');

$userId	= $decodedData['UserID'];
//$userId	= 18;


$sqlSelect = 	"SELECT name, topic, url " . 
				"  FROM topics T, subscriptions S, events E " .
				" WHERE S.user_id = ? " .
				"   AND E.id = S.event_id " .
				"   AND E.id = T.event_id " .
				" ORDER BY T.create_datetime DESC";

/*
$sqlSelect = 	"SELECT name, topic, url " . 
		" FROM topics T, subscription_notifications SN, subscriptions S, events E " .
		" WHERE T.id = SN.notification_id " . 
		" AND SN.subscription_id = S.id " .
		" AND E.id = T.event_id" .
		" AND S.user_id = ? ";
*/

// TODO: fix exception handling that was originally implemented using "Message" => "Success" (Note line 32 is commented out)
$stmtSelect = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmtSelect, $sqlSelect)) {
	$Message = 'Unable to fetch Topics from the database (Select Prepare)';
	$response[] = array("Message" => $Message);
	echo json_encode($response);
	exit();
} 
mysqli_stmt_bind_param($stmtSelect, "i", $userId);

if(mysqli_stmt_execute($stmtSelect)){
	$result = mysqli_stmt_get_result($stmtSelect);
	if(mysqli_num_rows($result) == 0){
		$response[] = array("Message" => "No Topics Found.");
		echo json_encode($response);
		exit();
	} else {
		while($row = mysqli_fetch_array($result)){
			$topics[] = array('name' => $row['name'],
							 'topic' => $row['topic'],
							 'url'   => $row['url']);
		}
//		$response[] = array("Message" => "Success");
		$response = array("Topics" => $topics);
		echo json_encode($response);
		exit();
	}
} else {
	$response[] = array("Message" => 'Unable to fetch Topicsfrom the database (Select Execute.');
	echo json_encode($response);
	exit();
}
