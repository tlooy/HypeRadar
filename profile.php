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
			<div class="row">
				<div class="col-md-12">
					<h2 class="mt-5">My Profile</h2>
					<p>List of Subscriptions in My Profile.</p>
					<p>Note: the Add New Subscriptions form and the Delete Subscriptions icon are not functional yet...</p>			
				</div>
			</div>        
   
			<div class="mt-5 mb-3 clearfix">
				<h2 class="pull-left">My Product Subscriptions</h2>
				<a href="./createSubscriptions.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Subscriptions</a>
			</div>
			<?php                    
				$sql = "SELECT S.id, P.name product_name, P.release_dt, G.name genre_name, F.name franchise_name " .
					" FROM products P, genres G, franchises F, subscriptions S " .
					"WHERE P.genre_id = G.id AND P.franchise_id = F.id " .
					"  AND S.product_id = P.id" . 
					"  AND S.user_id = " . $_SESSION["id"];
				if($result = mysqli_query($conn, $sql)){
					if(mysqli_num_rows($result) > 0){
						echo '<table class="table table-bordered table-striped">';
						echo "<thead>"; 
						echo "<tr> <th> Product Name </th>";
						echo "<th> 	Genre</th>";
						echo "<th> 	Franchise</th>";
						echo "<th> 	Release Date</th> </tr>";
						echo "</thead>";
						echo "<tbody>";
						while($row = mysqli_fetch_array($result)){
							echo "<tr>";
							echo "<td width=55%>" . $row['product_name'] . "</td>";
							echo "<td width=25%>" . $row['genre_name'] . "</td>";
							echo "<td width=25%>" . $row['franchise_name'] . "</td>";
							echo "<td width=25%>" . $row['release_dt'] . "</td>";
							echo "<td width=25%>";
							echo '<a href="./deleteSubscription.php?id=' . $row['id'] . '" title="Delete Subscription" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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

