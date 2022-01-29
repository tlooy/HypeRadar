<?php
	require_once "./header.php"; 
	require_once "./config.php";
	 

	if( isset($_POST['selected_event_ids'])) {

		for($x=0 ; $x < count($_POST['selected_event_ids']); $x++) {

	    	$sql = "INSERT INTO subscriptions (user_id, event_id) " .
	        	  " VALUES (" .$_SESSION['id'] . ',' . $_POST['selected_event_ids'][$x] . ');';

			mysqli_query($conn, $sql);
		}
	}
	else {
		// no ids were selected
		// ...
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Subscriptions</title>
</head>

<body>
	<div class="wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h2 class="mt-5">Available Events</h2>
					<p>List of Events that you currently do not subscribe to.</p>
				</div>
			</div>        
			
			<form action="createSubscriptions.php" method="post">   
				<?php
					mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);
					$sql = "SELECT E.id event_id, E.name event_name, E.event_from_datetime, E.event_to_datetime, G.name genre_name, F.name franchise_name " .
						" FROM events E, genres G, franchises F " .
						"WHERE E.genre_id = G.id AND E.franchise_id = F.id " .
						 " AND E.id NOT IN (SELECT S.event_id from subscriptions S WHERE S.user_id = " . $_SESSION["id"] . ")";

					if($result = mysqli_query($conn, $sql)){
						if(mysqli_num_rows($result) > 0){
							echo '<table class="table table-bordered table-striped">';
							echo "<thead>"; 
							echo "<tr><th>  				</th>";
							echo "<th> Event Id 			</th>";
							echo "<th> Event Name 			</th>";
							echo "<th> Genres				</th>";
							echo "<th> Franchise 			</th>";
							echo "<th> Event From Date 		</th>";
							echo "<th> Event To Date</th> 	</tr>";
							echo "</thead>";
							echo "<tbody>";
							$selected_event_ids = [];
							while($row = mysqli_fetch_array($result)){
								$event_id = $row['event_id'];
								echo "<tr>";
								echo "<td><input type='checkbox' name='selected_event_ids[]' value='" . $event_id . "'</td>";
								echo "<td width=15%>" . $event_id . "</td>";
								echo "<td width=55%>" . $row['event_name'] 			. "</td>";
								echo "<td width=25%>" . $row['genre_name'] 			. "</td>";
								echo "<td width=25%>" . $row['franchise_name'] 		. "</td>";
								echo "<td width=25%>" . $row['event_from_datetime']	. "</td>";
								echo "<td width=25%>" . $row['event_to_datetime']	. "</td>";
								echo "</tr>";
							}
							echo "</tbody>";              
							echo "</table>";
						// Free result set
						mysqli_free_result($result);
						} else{
							echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
						}
					} else{
						echo "Oops! Something went wrong. Please try again later.";
					}
	 
					// Close connection
//					mysqli_close($conn);
				?>
				<input type="submit" value="Submit" class="btn btn-danger">
				<a href="./mySubscriptions.php" class="btn btn-secondary">Cancel</a>

			</form>
		</div>
	</div>        
            
</body>
</html>
<?php require_once("./footer.php"); ?>	
