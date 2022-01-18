<?php
	require_once "./config.php";
	require_once "./header.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>My Topics</title>
</head>
<body>
	<div class="wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="mt-5 mb-3 clearfix">
						<h2 class="pull-left">My Topics</h2>
					</div>

					<?php
						$sql_topics = "  SELECT T.topic, T.id, T.url, TS.name status " .
										"  FROM topics T, topic_statuses TS, events E, subscriptions S " .
										" WHERE T.status_id = TS.id " .
										"   AND T.event_id = E.id " .
										"   AND E.id = S.event_id " .
										"   AND S.user_id = " . $_SESSION["id"] .
								// TODO: should we only show published topics or all topics?
								//			"   AND S.name = 'Published' " .
										" ORDER BY T.create_datetime DESC";

						if($result = mysqli_query($conn, $sql_topics)) {
							if(mysqli_num_rows($result) > 0){
								echo '<table class="table table-bordered table-striped">';
								echo "<thead> <tr>"; 
								echo "<th> Topic     </th>";
								echo "<th> Topic URL </th>";
								echo "<th> Status    </th>";
								echo "</tr> </thead>";
								echo "<tbody>";


								while($row = mysqli_fetch_array($result)){
									echo "<tr>";
									echo "<td width=40%>" . $row['topic']  . "</td>";
									echo "<td width=40%>" . $row['url']    . "</td>";
									echo "<td width=20%>" . $row['status'] . "</td>";
									echo "</tr>";
								}

								echo "</tbody>";              
								echo "</table>";
								// Free result set
								mysqli_free_result($result);
							} else {
								echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
							}
						} else {
							echo "Oops! Something went wrong. Please try again later.";
						}
					?>
				</div>        
			</div>        
		</div>   
	</div>
</body>
</html>
