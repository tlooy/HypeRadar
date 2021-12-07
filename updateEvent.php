<?php
// Include config file
require_once "./config.php";
require_once "./header.php";
 
// Define variables and initialize with empty values
$name = $description = $event_type_id = $genre_id = $franchise_id = $event_from_datetime = $event_to_datetime = $event_status = $price =  "";
$name_err = $description_err = $event_type_err = $genre_err = $franchise_err = $event_from_date_err = $event_to_date_err = $event_status_err = $price_err = "";
 
// Get drop list values for Event Types, Genres and Franchises
    $sqlEvent_types  = "SELECT * FROM event_types";
    $all_event_types = mysqli_query($conn,$sqlEvent_types);
	$sqlGenres 	     = "SELECT * FROM genres";
	$all_genres 	 = mysqli_query($conn,$sqlGenres);
	$sqlFranchises   = "SELECT * FROM franchises";
	$all_franchises  = mysqli_query($conn,$sqlFranchises);

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

    $event_type_id       = trim($_POST["Event_TypeId"]);
    $genre_id 			 = trim($_POST["GenreId"]);
    $franchise_id 		 = trim($_POST["FranchiseId"]);
    $event_from_datetime = trim($_POST["Event_from_datetime"]);
    $event_to_datetime   = trim($_POST["Event_to_datetime"]);
    $event_status 	     = trim($_POST["Event_status"]);
    $price		 	     = trim($_POST["price"]);
        
    // Check input errors before inserting in database
    if(empty($name_err) && empty($description_err)){
        // Prepare an update statement
        $sql = "UPDATE events SET name=?, description=?, event_type_id=?, genre_id=?, franchise_id=?, event_from_datetime=?, event_to_datetime=?, event_status=?, price=?,  WHERE id=?";

        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssiiisssdi", $param_name, $param_description, $param_event_type_id, $param_genre_id, 
                $param_franchise_id, $param_event_from_datetime, $param_event_to_datetime, $param_event_status, $param_price,  $param_id);
            
            // Set parameters
            $param_name 		         = $name;
            $param_description 	         = $description;
            $param_event_type_id         = $event_type_id;
            $param_genre_id              = $genre_id;
            $param_franchise_id          = $franchise_id;
            $param_event_from_datetime	 = $event_from_datetime;
            $param_event_to_datetime     = $event_to_datetime;
            $param_event_status          = $event_status;
            $param_price 		         = $price;
            $param_id 	                 = $id;
            
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
        $sql = "SELECT * FROM events WHERE id = ? ";
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
                    $name 			     = $row["name"];
                    $description 		 = $row["description"];
                    $event_type_id       = $row["genre_id"];
                    $genre_id 			 = $row["genre_id"];
                    $franchise_id 		 = $row["franchise_id"];
                    $event_from_datetime = $row["event_from_datetime"];
                    $event_to_datetime   = $row["event_to_datetime"];
                    $event_status   	 = $row["event_status"];
                    $price		 	     = $row["price"];
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
    <title>Update Event</title>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Event</h2>
                    <p>Please edit the input values and submit to update the Event record.</p>
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
                            <label>Select an Event Type</label>
                            <select name="Event_TypeId">
                            <?php while ($event_type = mysqli_fetch_array($all_genres,MYSQLI_ASSOC)):; ?>
                                <?php if ($event_type["id"] == $event_type_id): ?>
                                    <option selected = "selected" value="<?php echo $event_type["id"]; ?>">
                                    <?php echo $event_type["name"]; ?>
                                <?php else: ?>
                                    <option value="<?php echo $event_type["id"]; ?>">
                                    <?php echo $event_type["name"]; ?>                       
                                    </option>           
                                <?php endif; ?>
            
                            <?php endwhile; ?>
                            </select>
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

                        <div class="form-group">
                            <label>Event From Date</label>
                            <input type="date" name="Event_from_datetime" 	class="form-control <?php echo (!empty($event_from_datetime_err)) ? 'is-invalid' : ''; ?>" 		value="<?php echo $event_from_datetime; ?>">
                            <span class="invalid-feedback"><?php echo $event_from_datetime_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Event To Date</label>
                            <input type="date" name="Event_to_datetime"   class="form-control <?php echo (!empty($event_to_datetime_err)) ? 'is-invalid' : ''; ?>"      value="<?php echo $event_to_datetime; ?>">
                            <span class="invalid-feedback"><?php echo $event_to_datetime_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Event Status</label>
                            <input type="text" name="event_status" class="form-control <?php echo (!empty($event_status_err)) ? 'is-invalid' : ''; ?>" 	value="<?php echo $event_status; ?>">
                            <span class="invalid-feedback"><?php echo $event_status_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" 	class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" 			value="<?php echo $price; ?>" min="0" step="0.01" max="2500">
                            <span class="invalid-feedback"><?php echo $price_err;?></span>
                        </div>

                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="./admin.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>


