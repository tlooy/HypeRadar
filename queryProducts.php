<?php
// Include config file
	require_once "./config.php";
	
	$selected_product = $selected_genre = $selected_franchise = "--";
	
	//sql used to get drop list values
	$sqlGenres 	= "SELECT * FROM genres";
	$all_genres 	= mysqli_query($conn,$sqlGenres);
	$sqlFranchises  = "SELECT * FROM franchises";
	$all_franchises = mysqli_query($conn,$sqlFranchises);
	$sqlProducts  = "SELECT * FROM products";
	$all_products = mysqli_query($conn,$sqlProducts);

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$sql_select_list = "SELECT 	P.id, P.name product_name, P.description product_description, P.genre_id, " . 
						" P.franchise_id, G.name genre_name, F.name franchise_name, P.release_dt, P.release_dt_status, P.price " . 
				  " FROM 	products P, genres G, franchises F " . 
				  " WHERE 	P.genre_id = G.id " . 
				  " AND 	P.franchise_id = F.id ";
		$selected_product   = $_POST["selected_product"]; 
		$selected_genre     = $_POST["selected_genre"]; 
		$selected_franchise = $_POST["selected_franchise"]; 

		if ($selected_product != "--") {
			$sql_select = $sql_select_list . " AND P.name = '" .  $selected_product . "' order by P.name";		
		} elseif ($selected_genre != "--" && $selected_franchise != "--") {
			$sql_select = $sql_select_list . " AND P.genre_id = " .  $selected_genre . " AND P.franchise_id = " . $selected_franchise . " order by P.name";		
		} elseif ($_POST["selected_genre"] != "--") {
			$sql_select = $sql_select_list . " AND P.genre_id = " .  $selected_genre . " order by P.name";		
		} elseif ($_POST["selected_franchise"] != "--") {
			$sql_select = $sql_select_list . " AND P.franchise_id = " .  $selected_franchise . " order by P.name";		
		} else {
			$sql_select = $sql_select_list;		
		}
		$result = mysqli_query($conn, $sql_select);
	}    	
        mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Query Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <?php require_once("./headerUser.php"); ?>	

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Query Products</h2>
                    <p>Please fill this form and query to find Product records in the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Select a Product</label>
				<select name="selected_product">
				
		                	<option value="<?php echo "--"; ?>">
		                	<?php echo "--"; ?>

					<?php while ($product = mysqli_fetch_array($all_products,MYSQLI_ASSOC)):; ?>
			                	<option value="<?php echo $product["name"]; ?>">
			                	<?php echo $product["name"]; ?>
			                	</option>
					<?php endwhile; ?>
				</select>
                            <label>-- OR --</label>

                            <label>Select a Genre</label>
				<select name="selected_genre">
				
		                	<option value="<?php echo "--"; ?>">
		                	<?php echo "--"; ?>

					<?php while ($genre = mysqli_fetch_array($all_genres,MYSQLI_ASSOC)):; ?>
			                	<option value="<?php echo $genre["id"]; ?>">
			                	<?php echo $genre["name"]; ?>
			                	</option>
					<?php endwhile; ?>
				</select>

                            <label>Select a Franchise</label>
				<select name="selected_franchise">
				
		                	<option value="<?php echo "--"; ?>">
		                	<?php echo "--"; ?>

					<?php while ($franchise = mysqli_fetch_array($all_franchises,MYSQLI_ASSOC)):; ?>
			                	<option value="<?php echo $franchise["id"]; ?>">
			                	<?php echo $franchise["name"]; ?>
			                	</option>
					<?php endwhile; ?>
				</select>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Query">
                        <a href="./index.php" class="btn btn-secondary ml-2">Cancel</a>

			<div>        
				<iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=1&bgcolor=%23ffffff&ctz=America%2FLos_Angeles&src=dGxvb3kyNEBnbWFpbC5jb20&src=c3V6bG9veUBnbWFpbC5jb20&color=%23E67C73&color=%234285F4" 
				style="border:solid 1px #777" width="800" height="600" frameborder="0" scrolling="no"></iframe>
			</div>

	                <div class="row">
                	     <div class="col-md-12">
                                 <div class="mt-5 mb-3 clearfix">
                                      <h2 class="pull-left">Product List</h2>
                                 </div>
                                 <?php
                                           if(mysqli_num_rows($result) > 0){
                                                echo '<table class="table table-bordered table-striped">';
                                                echo "<thead>";
                                                echo "<tr>";
                                                echo "<th>Product Name</th>";
                                                echo "<th>Description</th>";
                                                echo "<th>Genre</th>";
                                                echo "<th>Franchise</th>";
                                                echo "<th>Release Date</th>";
                                                echo "<th>Release Date Status</th>";
                                                echo "<th>Price</th>";
                                                echo "<th>Comments</th>";
                                                echo "</tr>";
                                                echo "</thead>";
                                                echo "<tbody>";
                                                while($row = mysqli_fetch_array($result)){
                                                     echo "<tr>";
                                                     echo "<td>" . $row['product_name'] 		. "</td>";
                                                     echo "<td>" . $row['product_description'] 	. "</td>";
                                                     echo "<td>" . $row['genre_name'] 		. "</td>";
                                                     echo "<td>" . $row['franchise_name'] 		. "</td>";
                                                     echo "<td>" . $row['release_dt'] 		. "</td>";
                                                     echo "<td>" . $row['release_dt_status'] 		. "</td>";
                                                     echo "<td>" . $row['price'] 			. "</td>";
		                                     echo "<td>";
                                                         echo '<a href="./readProductComments.php?id=' . $row['id'] .'" class="mr-3" title="View Comments" data-toggle="tooltip"><span class="fa fa-book"></span></a>';
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

                                ?>
                           </div>
                      </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>

