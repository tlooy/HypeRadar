<?php
require_once "./config.php";
require_once "./header.php";

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file

    // Prepare a select statements
	$sql_topics = " SELECT T.topic, T.id " .
			"  FROM topics T, topic_statuses S " .
			" WHERE T.event_id = ? " .
			"   AND T.status_id = S.id " .
			"   AND S.name = 'Published' " .
			" ORDER BY create_datetime desc";
    
    if($stmt_topics = mysqli_prepare($conn, $sql_topics)){
        // Bind variables to the prepared statement as parameters
        // Set parameters
        $param_event_id = trim($_GET["id"]);

        mysqli_stmt_bind_param($stmt_topics, "i", $param_event_id);
        
        if(mysqli_stmt_execute($stmt_topics)){
            $result_topics = mysqli_stmt_get_result($stmt_topics);
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    } else {
    	echo "Trouble with the mysql_prepare()";
    }
    
    // Close connection
//    mysqli_close($conn);

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
    <title>View Topics</title>
</head>
<body>
	<div class="wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="mt-5 mb-3 clearfix">
						<h2 class="pull-left">Event Topics</h2>
					</div>

					<?php
					if(mysqli_num_rows($result_topics) > 0) {
						echo '<table class="table table-bordered table-striped">';
						echo "<thead> <tr>"; 
						echo "<th> Topic  </th>";
						echo "</tr> </thead>";
						echo "<tbody>";
						while($row = mysqli_fetch_array($result_topics)){
							echo "<tr>";
							echo "<td width=50%>" . $row['topic']  . "</td>";
							echo "</tr>";
						}
						echo "</tbody></table>";
					} else {
						echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
					}
					?>
				</div>        
			</div>        
			<div>
 	               	<button class="btn btn-secondary ml-2" onclick="history.back()"> Go Back </button>
			</div>
		</div>   
	</div>
</body>
</html>
