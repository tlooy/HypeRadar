<?php
	require_once "./header.php"; 
	require_once "./config.php";
	 

	if( isset($_POST['selected_product_ids'])) {

		for($x=0 ; $x < count($_POST['selected_product_ids']); $x++) {

	    	$sql = "INSERT INTO subscriptions (user_id, product_id) " .
	        	  " VALUES (" .$_SESSION['id'] . ',' . $_POST['selected_product_ids'][$x] . ');';

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
					<h2 class="mt-5">Available Products</h2>
					<p>List of Products that you currently do not subscribe to.</p>
				</div>
			</div>        
			
			<form action="createSubscriptions.php" method="post">   
				<?php
					mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);
					$sql = "SELECT P.id product_id, P.name product_name, P.release_dt, G.name genre_name, F.name franchise_name " .
						" FROM products P, genres G, franchises F " .
						"WHERE P.genre_id = G.id AND P.franchise_id = F.id " .
						 " AND P.id NOT IN (SELECT S.product_id from subscriptions S WHERE S.user_id = " . $_SESSION["id"] . ")";

					if($result = mysqli_query($conn, $sql)){
						if(mysqli_num_rows($result) > 0){
							echo '<table class="table table-bordered table-striped">';
							echo "<thead>"; 
							echo "<tr><th>  </th>";
							echo "<th> Product Id </th>";
							echo "<th> Product Name </th>";
							echo "<th> 	Genre</th>";
							echo "<th> 	Franchise</th>";
							echo "<th> 	Release Date</th> </tr>";
							echo "</thead>";
							echo "<tbody>";
							$selected_product_ids = [];
							while($row = mysqli_fetch_array($result)){
								$product_id = $row['product_id'];
								echo "<tr>";
								echo "<td><input type='checkbox' name='selected_product_ids[]' value='" . $product_id . "'</td>";
								echo "<td width=15%>" . $product_id . "</td>";
								echo "<td width=55%>" . $row['product_name'] . "</td>";
								echo "<td width=25%>" . $row['genre_name'] . "</td>";
								echo "<td width=25%>" . $row['franchise_name'] . "</td>";
								echo "<td width=25%>" . $row['release_dt'] . "</td>";
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
				<a href="./profile.php" class="btn btn-secondary">Cancel</a>

			</form>
		</div>
	</div>        
            
</body>
</html>
<?php require_once("./footer.php"); ?>	
