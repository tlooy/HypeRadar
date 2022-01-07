<?php
// Include config file
require_once("./header.php"); 
require_once "./config.php";
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>User Profile</title>
</head>

<body>
	<div class="wrapper">
		<div class="container-fluid">
   
			<div class="mt-5 mb-3 clearfix">
				<h2 class="pull-left">My Subscriptions</h2>
				<a href="./createSubscriptions.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Subscriptions</a>
			</div>
			<?php                    
				$sql = "SELECT S.id subscription_id, E.id event_id, E.name event_name, E.event_from_datetime, G.name genre_name, F.name franchise_name " .
					" FROM events E, genres G, franchises F, subscriptions S " .
					"WHERE E.genre_id = G.id AND E.franchise_id = F.id " .
					"  AND S.event_id = E.id" . 
					"  AND S.user_id = " . $_SESSION["id"];
				if($result = mysqli_query($conn, $sql)){
					if(mysqli_num_rows($result) > 0){
						echo '<table class="table table-bordered table-striped">';
						echo "<thead> <tr> "; 
						echo "<th> Event Name 		</th>";
						echo "<th> Genre 		</th>";
						echo "<th> Franchise 		</th>";
						echo "<th> Event From Date 	</th>";
						echo "</tr> </thead>";
						echo "<tbody>";
						while($row = mysqli_fetch_array($result)){
							echo "<tr>";
							echo "<td width=40%>" . $row['event_name'] 			. "</td>";
							echo "<td width=15%>" . $row['genre_name'] 			. "</td>";
							echo "<td width=15%>" . $row['franchise_name'] 		. "</td>";
							echo "<td width=20%>" . $row['event_from_datetime'] . "</td>";
							echo "<td width=10%>";
							echo '<a href="./deleteSubscription.php?id=' . $row['subscription_id'] . '" title="Delete Subscription" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
							echo '<a href="./readTopics.php?id=' . $row['event_id'] . '" title="Read Topics" data-toggle="tooltip"><span class="fa fa-book"></span></a>';
							echo "</td>";
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
				mysqli_close($conn);
			?>
			</div>
		</div>        
        
    
</body>
</html>
<?php require_once("./footer.php"); ?>	

