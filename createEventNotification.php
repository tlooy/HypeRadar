<?php
	require_once "./config.php";
	require_once "./header.php";

	$id = "";

	if(isset($_POST["id"]) && !empty($_POST["id"])){
    		// Get hidden input value
		$id = $_POST["id"];
	}


	// Check existence of id parameter before processing further
	if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
	        // Get URL parameter
		$id = trim($_GET["id"]);
	}

	$eventId = $id;
	$topic = $source_id = $status = "";
	$topic_err = $source_id_err = $status_err = "";

	$sql_Sources 	= "SELECT * FROM sources";
	$all_Sources 	= mysqli_query($conn,$sql_Sources);

	if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	    	// Validate topic
		$input_topic = mysqli_real_escape_string($conn,$_POST['Topic']);

		if(empty($input_topic)){
    		$topic_err = "Please enter a topic.";
		} elseif(!filter_var($input_topic, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
    		$name_err = "Please enter a valid topic.";
		} else{
    		$topic = $input_topic;
		}


		$eventId 		= mysqli_real_escape_string($conn,$_POST['EventId']);
		$sourceId 		= mysqli_real_escape_string($conn,$_POST['SourceId']);
		$status			= mysqli_real_escape_string($conn,$_POST['Status']);
		$systemDt 		= date("Y-m-d H:i:s");
		
		if(empty($notication_err) ){
			$sql_insert =
			"INSERT INTO notifications (topic, event_id, source_id, status, create_datetime) VALUES (?, ?, ?, ?, ?)";
         
	        	if($stmt = mysqli_prepare($conn, $sql_insert)){
	        	    mysqli_stmt_bind_param($stmt, "siiss", $param_topic, $param_eventId, $param_sourceId, $param_status, $param_create_datetime);
	        	    $param_topic			= $topic;
        		    $param_eventId 			= $eventId;
       		    	$param_sourceId 		= $sourceId;
       		    	$param_status		 	= $status;
       		    	$param_create_datetime 	= $systemDt;

	        	    if(mysqli_stmt_execute($stmt)){
	        	        header("location: ./contributor.php");
	        	        exit();
	        	    } else{
	        	        echo "Oops! Something went wrong. Please try again later.";
echo mysqli_stmt_error($stmt);
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
    <title>Create Event Notification</title>
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
                    <h2 class="mt-5">Create Event Notification</h2>
                    <p>Please fill this form and submit to add an Event Notification to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    
                        <div class="form-group">
                            <label>Topic</label>
                            <input type="text" name="Topic" class="form-control <?php echo (!empty($topic_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $topic; ?>">
                            <span class="invalid-feedback"><?php echo $topic_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Select a Source</label>
							<select name="SourceId">
								<?php while ($source = mysqli_fetch_array($all_Sources,MYSQLI_ASSOC)):; ?>			
			                		<option value="<?php echo $source["id"]; ?>">
			                		<?php echo $source["name"]; ?>
			                		</option>			
								<?php endwhile; ?>
							</select>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" name="Status" class="form-control <?php echo (!empty($status_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $status; ?>">
                            <span class="invalid-feedback"><?php echo $status_err;?></span>
                        </div>
                        
                        <input type="hidden" name="EventId" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                    	<button class="btn btn-secondary ml-2" onclick="history.back()"> Go Back </button>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>

</html>

