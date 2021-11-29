<?php
// Include config file
	require_once "./config.php";
	
	$name = $description = $genreName = $genreId = $franchiseName = $franchiseId = $releaseDt = $releaseDtStatus = $price = "";
	$name_err = $description_err = $release_dt_status = "";

	$sqlGenres 	= "SELECT * FROM `genres`";
	$all_genres 	= mysqli_query($conn,$sqlGenres);
	$sqlFranchises  = "SELECT * FROM `franchises`";
	$all_franchises = mysqli_query($conn,$sqlFranchises);

	if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	    	// Validate name
		$input_name = mysqli_real_escape_string($conn,$_POST['name']);
    		if(empty($input_name)){
        		$name_err = "Please enter a name.";
    		} elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
        		$name_err = "Please enter a valid name (numbers and letters only).";
    		} else{
        		$name = $input_name;
    		}

		$input_description = mysqli_real_escape_string($conn,$_POST['description']);
    		if(empty($input_description)){
        		$description_err = "Please enter a description.";
    		} elseif(!filter_var($input_description, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
        		$description_err = "Please enter a valid description (numbers and letters only}.";
    		} else{
        		$description = $input_description;
    		}

		$genreName 		= mysqli_real_escape_string($conn,$_POST['Genre_name']);
		$genreId 		= mysqli_real_escape_string($conn,$_POST['GenreId']);
		$franchiseName		= mysqli_real_escape_string($conn,$_POST['Franchise_name']);
		$franchiseId 		= mysqli_real_escape_string($conn,$_POST['FranchiseId']);
		$releaseDtStatus 	= mysqli_real_escape_string($conn,$_POST['ReleaseDtStatus']);
		$price		 	= mysqli_real_escape_string($conn,$_POST['Price']);
		
		if(!empty($_POST['ReleaseDt'])) {
			$rawReleaseDt 	= mysqli_real_escape_string($conn,$_POST['ReleaseDt']);
			$releaseDt	= date('Y-m-d', strtotime($rawReleaseDt));
		} else {
			$releaseDt	= null;
		}

		if(empty($name_err) && empty($description_err) ){
		
			$sql_insert =
			"INSERT INTO products (name, description, genre_id, franchise_id, release_dt, release_dt_status, price) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
	        	if($stmt = mysqli_prepare($conn, $sql_insert)){

	        	    mysqli_stmt_bind_param($stmt, "ssiissd", $param_name, $param_description, $param_genreId, $param_franchiseId, $param_releaseDt, $param_releaseDtStatus, $param_price);
	        	    $param_name 		= $name;
        		    $param_description 	= $description;
       		    $param_genreId 		= $genreId;
       		    $param_franchiseId 	= $franchiseId;
       		    $param_releaseDt 		= $releaseDt;
       		    $param_releaseDtStatus 	= $releaseDtStatus;
       		    $param_price	 	= $price;

	        	    if(mysqli_stmt_execute($stmt)){
	        	        header("location: ./index.php");
	        	        exit();
	        	    } else{
	        	        echo "Oops! Something went wrong. Please try again later.";
	        	    }
        		} else {
	        	        echo "Something went wrong with the mysqli_prepare.";
        	
        		}
         
        	mysqli_stmt_close($stmt);
    		}
    	}
    	mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Product</h2>
                    <p>Please fill this form and submit to add a Product record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $description; ?>">
                            <span class="invalid-feedback"><?php echo $description_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Select a Genre</label>
				<select name="GenreId">
					<?php while ($genre = mysqli_fetch_array($all_genres,MYSQLI_ASSOC)):; ?>
			
			                	<option value="<?php echo $genre["id"]; ?>">
			                	<?php echo $genre["name"]; ?>
			                	</option>			
			
					<?php endwhile; ?>
				</select>
                        </div>

                        <div class="form-group">
                            <label>Select a Franchise</label>
				<select name="FranchiseId">
					<?php while ($franchise = mysqli_fetch_array($all_franchises,MYSQLI_ASSOC)):; ?>
			
			                	<option value="<?php echo $franchise["id"]; ?>">
			                	<?php echo $franchise["name"]; ?>
			                	</option>			
			
					<?php endwhile; ?>
				</select>
                        </div>

                        <div class="form-group">
                            <label>Release Date</label>
                            <input type="date" name="ReleaseDt" class="form-control" value="<?php echo $releaseDt; ?>">
                            <span class="invalid-feedback"><?php echo $description_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Release Date Status</label>
                            <input type="text" name="ReleaseDtStatus" class="form-control value="<?php echo $releaseDtStatus; ?>">
                            <span class="invalid-feedback"><?php echo $release_dt_status_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="Price" class="form-control value="<?php echo $price; ?>">
                            <span class="invalid-feedback"><?php echo $price_err;?></span>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Submit">

                        <a href="./index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>

</html>

