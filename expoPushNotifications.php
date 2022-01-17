<?php
require_once "./config.php";
require_once "./header.php";

// Check existence of id parameter before processing further
if(isset($_GET["topic_id"]) && !empty(trim($_GET["topic_id"]))){
	$sql_pushTokens =	"SELECT T.topic, T.url, E.name, ND.notification_token " .
				"  FROM topics T, events E, subscriptions S, users U, notification_devices ND " .
				" WHERE T.event_id = E.id " .
				"   AND E.id = S.event_id " .
				"   AND S.user_id = U.id "  .
				"   AND U.id = ND.user_id " .
				"   AND T.id = ? " .
				" ORDER BY create_datetime desc";
    
	if($stmt_pushTokens = mysqli_prepare($conn, $sql_pushTokens)){
		$param_topic_id = trim($_GET["topic_id"]);

		mysqli_stmt_bind_param($stmt_pushTokens, "i", $param_topic_id);
        
		if(mysqli_stmt_execute($stmt_pushTokens)){
			$result_pushTokens = mysqli_stmt_get_result($stmt_pushTokens);
		} else {
			header("location: ./error.php");
		}
	} else {
		header("location: ./error.php");
	}

	while($row = mysqli_fetch_array($result_pushTokens)){
		$payload_tokens[] = $row['notification_token'];
		$payload_body = "You have a ping from Hype Radar!\n" . 
										$row['name']  . ": \n" . 
										$row['topic'] . ": \n" . 
										$row['url'];
	}
	$payload = array(
	'to' => $payload_tokens,
	'sound' => 'default',
	'body' => $payload_body,
    );
print_r($payload);

} else {
	header("location: ./error.php");
	exit();

}   

/*
    $payload = array(
        'to' => 'ExponentPushToken[xRVMIeId6t0BQS_eY94El7]',
        'sound' => 'default',
        'body' => $payload_body,
    );
*/

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://exp.host/--/api/v2/push/send",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($payload),
  CURLOPT_HTTPHEADER => array(
    "Accept: application/json",
    "Accept-Encoding: gzip, deflate",
    "Content-Type: application/json",
    "cache-control: no-cache",
    "host: exp.host"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Event</title>
</head>
<body>
	<div class="wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="mt-5 mb-3 clearfix">
						<h2 class="pull-left">Expo Notifications Sent</h2>
					</div>
                            </div>

					<?php
					if(mysqli_num_rows($result_pushTokens) > 0) {
						echo '<table class="table table-bordered table-striped">';
						echo "<thead> <tr>"; 
						echo "<th> Event  </th>";
						echo "<th> Topic  </th>";
						echo "<th> Notification Token </th>";
						echo "</tr> </thead>";
						echo "<tbody>";
						while($row = mysqli_fetch_array($result_pushTokens)){
							echo "<tr>";
							echo "<td width=20%>" . $row['name']  		. "</td>";
							echo "<td width=20%>" . $row['topic']  		. "</td>";
							echo "<td width=60%>" . $row['notification_token'] 	. "</td>";
							echo "</tr>";
						}
						echo "</tbody> </table>";
					} else {
						echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
					}
					?>
				</div>
			</div> 
	                <button class="btn btn-secondary ml-2" onclick="history.back()"> Go Back </button>
		</div>
	</div>
</body>
</html>

