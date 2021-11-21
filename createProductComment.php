<?php
	require_once "config.php";
	if(isset($_POST["id"]) && !empty($_POST["id"])){
    		// Get hidden input value
		$id = $_POST["id"];
	}


	// Check existence of id parameter before processing further
	if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
	        // Get URL parameter
		$id = trim($_GET["id"]);
	}

	$productId = trim($_GET["id"]);
	$comment = $source_id = $hyperlink = "";
	$comment_err = $source_id_err = $hyperlink_err = "";

	$sql_Sources 	= "SELECT * FROM sources";
	$all_Sources 	= mysqli_query($conn,$sql_Sources);

	if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	    	// Validate name
		$input_comment = mysqli_real_escape_string($conn,$_POST['Comment']);
    		if(empty($input_comment)){
        		$name_err = "Please enter a comment.";
    		} elseif(!filter_var($input_comment, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        		$name_err = "Please enter a valid comment.";
    		} else{
        		$comment = $input_comment;
    		}


		$productId 		= mysqli_real_escape_string($conn,$_POST['ProductId']);
		$sourceName		= mysqli_real_escape_string($conn,$_POST['SourceName']);
		$sourceId 		= mysqli_real_escape_string($conn,$_POST['SourceId']);
		$hyperlink	 	= mysqli_real_escape_string($conn,$_POST['Hyperlink']);
		$systemDt 		= date("Y-m-d H:i:s");
		
		if(empty($comment_err) ){
			$sql_insert =
			"INSERT INTO comments (comment, product_id, source_id, hyperlink, create_dt) VALUES (?, ?, ?, ?, ?)";
         
	        	if($stmt = mysqli_prepare($conn, $sql_insert)){
	        	    mysqli_stmt_bind_param($stmt, "siiss", $param_comment, $param_productId, $param_sourceId, $param_hyperlink, $param_create_dt);
	        	    $param_comment 		= $comment;
        		    $param_productId 		= $productId;
       		    $param_sourceId 		= $sourceId;
       		    $param_hyperlink	 	= $hyperlink;
       		    $param_create_dt	 	= $systemDt;

	        	    if(mysqli_stmt_execute($stmt)){
	        	        header("location: index.php");
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
    <title>Create Product Comment</title>
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
                    <h2 class="mt-5">Create Product Comment</h2>
                    <p>Please fill this form and submit to add a Product Comment to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    
                        <div class="form-group">
                            <label>Comment</label>
                            <input type="text" name="Comment" class="form-control <?php echo (!empty($comment_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $comment; ?>">
                            <span class="invalid-feedback"><?php echo $comment_err;?></span>
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
                            <label>Hyperlink</label>
                            <input type="text" name="Hyperlink" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $hyperlink; ?>">
                            <span class="invalid-feedback"><?php echo $hyperlink_err;?></span>
                        </div>
                        
                        <input type="hidden" name="ProductId" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>

</html>

