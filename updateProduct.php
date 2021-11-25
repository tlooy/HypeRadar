<?php
// Include config file
require_once "./config.php";
 
// Define variables and initialize with empty values
$name = $description = $release_dt = $release_dt_status = $price = $genre_id = $franchise_id = "";
$name_err = $description_err = $genre_err = $franchise_err = $release_dt_err = $release_dt_status_err = $price_err = "";
 
// Get drop list values for Genres and Franchises
	$sqlGenres 	= "SELECT * FROM `genres`";
	$all_genres 	= mysqli_query($conn,$sqlGenres);
	$sqlFranchises  = "SELECT * FROM `franchises`";
	$all_franchises = mysqli_query($conn,$sqlFranchises);

// Processing form data when form is submitted

if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
        
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
        $name_err = "Please enter a valid name (numbers and letters only).";
    } else{
        $name = $input_name;
    }

    // Validate description
    $input_description = trim($_POST["description"]);
    if(empty($input_description)){
        $description_err = "Please enter a description.";
    } elseif(!filter_var($input_description, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
        $name_err = "Please enter a valid description (numbers and letters only}.";
    } else{
        $description = $input_description;
    }

    $genre_id 			= trim($_POST["GenreId"]);
    $franchise_id 		= trim($_POST["FranchiseId"]);
    $release_dt 		= trim($_POST["release_dt"]);
    $release_dt_status 	= trim($_POST["release_dt_status"]);
    $price		 	= trim($_POST["price"]);
        
    // Check input errors before inserting in database
    if(empty($name_err) && empty($description_err)){
        // Prepare an update statement
        $sql = "UPDATE products SET name=?, description=?, release_dt=?, release_dt_status=?, price=?, genre_id=?, franchise_id=? WHERE id=?";

        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssdiii", $param_name, $param_description, $param_release_dt, $param_release_dt_status, $param_price, $param_genre_id, $param_franchise_id, $param_id);
            
            // Set parameters
            $param_name 		= $name;
            $param_description 	= $description;
            $param_release_dt		= $release_dt;
            $param_release_dt_status	= $release_dt_status;
            $param_price 		= $price;
            $param_genre_id 		= $genre_id;
            $param_franchise_id 	= $franchise_id;
            $param_id 			= $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: ./index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($conn);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id    =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM products WHERE id = ? ";
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $name 			= $row["name"];
                    $description 		= $row["description"];
                    $genre_id 			= $row["genre_id"];
                    $franchise_id 		= $row["franchise_id"];
                    $release_dt 		= $row["release_dt"];
                    $release_dt_status 	= $row["release_dt_status"];
                    $price		 	= $row["price"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: ./error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($conn);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: ./error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                    <h2 class="mt-5">Update Product</h2>
                    <p>Please edit the input values and submit to update the Product record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" 		class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" 			value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" 	class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>" 		value="<?php echo $description; ?>">
                            <span class="invalid-feedback"><?php echo $description_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Release Date</label>
                            <input type="date" name="release_dt" 	class="form-control <?php echo (!empty($release_dt_err)) ? 'is-invalid' : ''; ?>" 		value="<?php echo $release_dt; ?>">
                            <span class="invalid-feedback"><?php echo $release_dt_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Release Date Status</label>
                            <input type="text" name="release_dt_status" class="form-control <?php echo (!empty($release_dt_status_err)) ? 'is-invalid' : ''; ?>" 	value="<?php echo $release_dt_status; ?>">
                            <span class="invalid-feedback"><?php echo $release_dt_status_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" 	class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" 			value="<?php echo $price; ?>" min="0" step="0.01" max="2500">
                            <span class="invalid-feedback"><?php echo $price_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Select a Genre</label>
				<select name="GenreId">
					<?php while ($genre = mysqli_fetch_array($all_genres,MYSQLI_ASSOC)):; ?>
						<?php if ($genre["id"] == $genre_id): ?>
				                	<option selected = "selected" value="<?php echo $genre["id"]; ?>">
				                	<?php echo $genre["name"]; ?>
						<?php else: ?>
				                	<option value="<?php echo $genre["id"]; ?>">
				                	<?php echo $genre["name"]; ?>						
			                		</option>			
						<?php endif; ?>
			
					<?php endwhile; ?>
				</select>
                        </div>

                        <div class="form-group">
                            <label>Select a Franchise</label>
				</select>
				<select name="FranchiseId">
					<?php while ($franchise = mysqli_fetch_array($all_franchises,MYSQLI_ASSOC)):; ?>
						<?php if ($franchise["id"] == $franchise_id): ?>
				                	<option selected = "selected" value="<?php echo $franchise["id"]; ?>">
				                	<?php echo $franchise["name"]; ?>
						<?php else: ?>
				                	<option value="<?php echo $franchise["id"]; ?>">
				                	<?php echo $franchise["name"]; ?>						
			                		</option>			
						<?php endif; ?>
					<?php endwhile; ?>
				</select>

                        </div>



                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="./index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>


