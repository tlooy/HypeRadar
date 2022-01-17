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
	$topic = $source_id = $status = $url = "";
	$topic_err = $source_id_err = $status_err = $url_err = "";

	$sql_Sources 	= "SELECT * FROM sources";
	$all_Sources 	= mysqli_query($conn,$sql_Sources);
	$sql_Statuses 	= "SELECT * FROM topic_statuses";
	$all_Statuses 	= mysqli_query($conn,$sql_Statuses);

	if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	    	// Validate topic
		$input_topic = mysqli_real_escape_string($conn,$_POST['Topic']);

		if(empty($input_topic)){
    		$topic_err = "Please enter a topic.";
		} elseif(!filter_var($input_topic, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
    		$name_err = "Please enter a valid topic.";
		} else{
    		$topic = $input_topic;
		}

	    	// Validate topic URL
		$input_url = mysqli_real_escape_string($conn,$_POST['URL']);

		if(empty($input_url)){
    		$url_err = "Please enter a topic URL.";
		} elseif(!filter_var($input_topic, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$\/:/")))){
    		$name_err = "Please enter a valid topic URL.";
		} else{
    		$url = $input_url;
		}

		$eventId 		= mysqli_real_escape_string($conn,$_POST['EventId']);
		$sourceId 		= mysqli_real_escape_string($conn,$_POST['SourceId']);
		$statusId		= mysqli_real_escape_string($conn,$_POST['StatusId']);
		$systemDt 		= date("Y-m-d H:i:s");
		
		if(empty($notication_err) ){
			$sql_insert =
			"INSERT INTO topics (topic, event_id, source_id, status_id, create_datetime, creator_user_id, url) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
	        	if($stmt = mysqli_prepare($conn, $sql_insert)){
	        	    mysqli_stmt_bind_param($stmt, "siissis", $param_topic, $param_eventId, $param_sourceId, $param_statusId, 
	        	    	$param_create_datetime, $param_creator_user_id, $param_url);
	        	    $param_topic			= $topic;
        		    $param_eventId 			= $eventId;
       		    	$param_sourceId 		= $sourceId;
       		    	$param_statusId		 	= $statusId;
       		    	$param_create_datetime 	= $systemDt;
       		    	$param_creator_user_id 	= $_SESSION['id'];
       		    	$param_url				= $url;

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
    <title>Create Event Topic</title>
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
                    <h2 class="mt-5">Create Event Topic</h2>
                    <p>Please fill this form and submit to add an Event Topic to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    
                        <div class="form-group">
                            <label>Topic</label>
                            <input type="text" name="Topic" class="form-control <?php echo (!empty($topic_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $topic; ?>">
                            <span class="invalid-feedback"><?php echo $topic_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Topic URL</label>
                            <input type="text" name="URL" class="form-control <?php echo (!empty($url_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $url; ?>">
                            <span class="invalid-feedback"><?php echo $url_err;?></span>
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
                            <label>Select a Topic Status</label>
							<select name="StatusId">
								<?php while ($source = mysqli_fetch_array($all_Statuses,MYSQLI_ASSOC)):; ?>			
			                		<option value="<?php echo $source["id"]; ?>">
			                		<?php echo $source["name"]; ?>
			                		</option>			
								<?php endwhile; ?>
							</select>
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

