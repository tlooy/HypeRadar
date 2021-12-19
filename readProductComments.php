<?php
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "./config.php";

    $param_event_id = trim($_GET["id"]);

    // Prepare a select statements
    $sql_comments = 	" SELECT * " . 
			"   FROM notifications N, sources S " .
			"  WHERE N.source_id = S.id " .
			"    AND event_id = " . $param_event_id . 
    			"  ORDER BY create_datetime desc";

    if($stmt_comments = mysqli_prepare($conn, $sql_comments)){
        // Bind variables to the prepared statement as parameters
        // Set parameters

        mysqli_stmt_bind_param($stmt_comments, "i", $param_event_id);
        
        // Attempt to execute the prepared statement

        
        if(mysqli_stmt_execute($stmt_comments)){
            $result_event_comments = mysqli_stmt_get_result($stmt_comments);
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    } else {
    	echo "Trouble with the mysql_prepare()";
    }
    
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($conn);

} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: ./error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Event Comments</title>
    
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
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php
                        if(mysqli_num_rows($result_event_comments) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>"; 
                                	 echo "<tr>"; 
                                	 echo "<th> Comment 		</th> ";
	                                echo "<th> Source 		</th> ";
	                                echo "<th> Link to Source 	</th> ";
	                                echo "<th> Create Date 	</th> "; 
                                	echo "</tr>"; 
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result_event_comments)){
                                    echo "<tr>";
                                    echo "<td >" . $row['comment'] . "</td> ";
                                    echo "<td >" . $row['name'] . "</td>";
                                    echo "<td >" . $row['hyperlink'] . "</td>";
                                    echo "<td >" . $row['create_dt'] . "</td>";
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

                    <p><a href="javascript:window.history.back();" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
