<?php
// Include config file
require_once "./config.php";
require_once "./header.php";
 
// Define variables and initialize with empty values
$topic = $event_id = $source_id = $status_id =  "";
$topic_err = $event_id_err = $source_err = $status_err = "";
 
// Get drop list values for Event Topic Status values
    $sql_statuses = "SELECT * FROM topic_statuses";
    $all_statuses = mysqli_query($conn,$sql_statuses);
    $sql_sources = "SELECT * FROM sources";
    $all_sources = mysqli_query($conn,$sql_sources);

// Processing form data when form is submitted

if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
        
    // Validate topic
    $input_topic = trim($_POST["topic"]);
    if(empty($input_topic)){
        $topic_err = "Please enter a topic.";
    } elseif(!filter_var($input_topic, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
        $topic_err = "Please enter a valid topic (numbers and letters only).";
    } else{
        $topic = $input_topic;
    }

    $event_id        = mysqli_real_escape_string($conn,$_POST['EventId']);
    $source_id       = mysqli_real_escape_string($conn,$_POST['SourceId']);
    $status_id       = mysqli_real_escape_string($conn,$_POST['StatusId']);
        
    // Check input errors before inserting in database
    if(empty($topic_err)){
        // Prepare an update statement
        $sql = "UPDATE topics SET topic=?, source_id=?, status_id=? WHERE id=?";

        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "siii", $param_topic, $param_source_id, $param_status_id, $param_id);
            
            // Set parameters
            $param_topic 		         = $topic;
            $param_source_id             = $source_id;
            $param_status_id             = $status_id;
            $param_id 	                 = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: ./contributor.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        } else {
            echo "Error in mysqli_prepare";
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
        $sql = "SELECT * FROM topics WHERE id = ? ";
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
                    $topic      = $row["topic"];
                    $event_id   = $row["event_id"];
                    $source_id  = $row["source_id"];
                    $status_id  = $row["status_id"];

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
                    <h2 class="mt-5">Update Event Topic</h2>
                    <p>Please edit the input values and submit to update the Event Topic record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Topic</label>
                            <input type="text" name="topic" class="form-control <?php echo (!empty($topic_err)) ? 'is-invalid' : ''; ?>" 			value="<?php echo $topic; ?>">
                            <span class="invalid-feedback"><?php echo $topic_err;?></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Select a Source</label>
                            <select name="SourceId">
                            <?php while ($source = mysqli_fetch_array($all_sources,MYSQLI_ASSOC)):; ?>
                                <?php if ($source["id"] == $source_id): ?>
                                    <option selected = "selected" value="<?php echo $source["id"]; ?>">
                                    <?php echo $source["name"]; ?>
                                <?php else: ?>
                                    <option value="<?php echo $source["id"]; ?>">
                                    <?php echo $source["name"]; ?>                       
                                    </option>           
                                <?php endif; ?>
            
                            <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Select a Status</label>
                            <select name="StatusId">
                            <?php while ($status = mysqli_fetch_array($all_statuses,MYSQLI_ASSOC)):; ?>
                                <?php if ($status["id"] == $status_id): ?>
                                    <option selected = "selected" value="<?php echo $status["id"]; ?>">
                                    <?php echo $status["name"]; ?>
                                <?php else: ?>
                                    <option value="<?php echo $status["id"]; ?>">
                                    <?php echo $status["name"]; ?>                       
                                    </option>           
                                <?php endif; ?>
            
                            <?php endwhile; ?>
                            </select>
                        </div>


                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="hidden" name="EventId" value="<?php echo $event_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="./contributor.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>


