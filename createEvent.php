<?php
	require_once "./config.php";
	require_once "./header.php";
	
	$name = $description = $type = $genreName = $genreId = $franchiseName = $franchiseId = 
			$eventFromDatetime = $eventToDatetime =$eventStatus = $price = "";
	$name_err = $description_err = $eventFromDate_err = $eventToDate_err = $eventStatus_err = $price_err = "";

	$sqlGenres 	     = "SELECT * FROM genres";
	$all_genres 	 = mysqli_query($conn,$sqlGenres);
	$sqlFranchises   = "SELECT * FROM franchises";
	$all_franchises  = mysqli_query($conn,$sqlFranchises);
	$sqlEventTypes   = "SELECT * FROM event_types";
	$all_eventTypes = mysqli_query($conn,$sqlEventTypes);

	if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	    	// Validate name
		$input_name = mysqli_real_escape_string($conn,$_POST['name']);
    		if(empty($input_name)){
        		$name_err = "Please enter an Event name.";
    		} elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
        		$name_err = "Please enter a valid Event name (numbers and letters only).";
    		} else{
        		$name = $input_name;
    		}

		$input_description = mysqli_real_escape_string($conn,$_POST['description']);
    		if(empty($input_description)){
        		$description_err = "Please enter an Event description.";
    		} elseif(!filter_var($input_description, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
        		$description_err = "Please enter a valid Event description (numbers and letters only}.";
    		} else{
        		$description = $input_description;
    		}

		$eventTypeId 	= mysqli_real_escape_string($conn,$_POST['EventTypeId']);
		$genreName 		= mysqli_real_escape_string($conn,$_POST['Genre_name']);
		$genreId 		= mysqli_real_escape_string($conn,$_POST['GenreId']);
		$franchiseName	= mysqli_real_escape_string($conn,$_POST['Franchise_name']);
		$franchiseId 	= mysqli_real_escape_string($conn,$_POST['FranchiseId']);
		$eventStatus 	= mysqli_real_escape_string($conn,$_POST['ReleaseDtStatus']);
		$price		 	= mysqli_real_escape_string($conn,$_POST['Price']);
		
		if(!empty($_POST['EventFromDatetime'])) {
			$rawEventFromDatetime 	= mysqli_real_escape_string($conn,$_POST['EventFromDatetime']);
			$eventFromDatetime		= date('Y-m-d', strtotime($rawEventFromDatetime));
		} else {
			$eventFromDatetime	= null;
		}

		if(!empty($_POST['EventToDatetime'])) {
			$rawEventToDatetime 	= mysqli_real_escape_string($conn,$_POST['EventToDatetime']);
			$eventToDatetime		= date('Y-m-d', strtotime($rawEventToDatetime));
		} else {
			$eventToDatetime = $eventFromDatetime;
		}

		if(empty($name_err) && empty($description_err) ){
		
			$sql_insert =
			"INSERT INTO events (name, description, event_type_id, genre_id, franchise_id, event_from_datetime, event_to_datetime, event_status, price) " . 
			" VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
	        	if($stmt = mysqli_prepare($conn, $sql_insert)){
					mysqli_stmt_bind_param($stmt, "ssiiisssd", 
						$param_name, $param_description, $param_eventTypeId, $param_genreId, $param_franchiseId, 
	        	    	$param_eventFromDatetime, $param_eventToDatetime, $param_eventStatus, $param_price);
	        	    $param_name 				= $name;
        		    $param_description 			= $description;
	       		    $param_eventTypeId 			= $eventTypeId;
	       		    $param_genreId 				= $genreId;
	       		    $param_franchiseId 			= $franchiseId;
	       		    $param_eventFromDatetime	= $eventFromDatetime;
	       		    $param_eventToDatetime 		= $eventToDatetime;
	       		    $param_eventStatus 			= $releaseDtStatus;
	       		    $param_price	 			= $price;

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
    <title>Create Event</title>
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
                    <h2 class="mt-5">Create Event</h2>
                    <p>Please fill this form and submit to add an Event to the database.</p>
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
                            <label>Select an Event Type</label>
							<select name="EventTypeId">
							<?php 
								while ($eventType = mysqli_fetch_array($all_eventTypes,MYSQLI_ASSOC)):; ?>
			                		<option value="<?php echo $eventType["id"]; ?>">
			                			<?php echo $eventType["name"]; ?>
			                		</option>			
								<?php endwhile; ?>
							</select>
                        </div>

                        <div class="form-group">
                            <label>Select a Genre</label>
							<select name="GenreId">
							<?php 
								while ($genre = mysqli_fetch_array($all_genres,MYSQLI_ASSOC)):; ?>
			                		<option value="<?php echo $genre["id"]; ?>">
			                			<?php echo $genre["name"]; ?>
			                		</option>			
			
								<?php endwhile; ?>
							</select>
                        </div>

                        <div class="form-group">
                            <label>Select a Franchise</label>
							<select name="FranchiseId">
							<?php 
								while ($franchise = mysqli_fetch_array($all_franchises,MYSQLI_ASSOC)):; ?>
				                	<option value="<?php echo $franchise["id"]; ?>">
					                	<?php echo $franchise["name"]; ?>
				                	</option>			
							<?php endwhile; ?>
							</select>
                        </div>

                        <div class="form-group">
                            <label>Event From Date</label>
                            <input type="date" required name="EventFromDatetime" class="form-control" value="<?php echo $eventFromDatetime; ?>">
                            <span class="invalid-feedback"><?php echo $eventFromDate_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Event To Date</label>
                            <input type="date" name="EventToDatetime" class="form-control" value="<?php echo $eventToDatetime; ?>">
                            <span class="invalid-feedback"><?php echo $eventToDate_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Event Status</label>
                            <input type="text" name="EventStatus" class="form-control value="<?php echo $eventStatus; ?>>
                            <span class="invalid-feedback"><?php echo $eventStatus_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="Price" step="0.01" class="form-control value="<?php echo $price; ?>>
                            <span class="invalid-feedback"><?php echo $price_err;?></span>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Submit">

                        <a href="./admin.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>

</html>

