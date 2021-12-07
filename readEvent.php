<?php
require_once "./config.php";
require_once "./header.php";

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file

    // Prepare a select statements
    $sql_event   	= "SELECT * FROM events WHERE id = ?";
    $sql_genre 	    = "SELECT * FROM genres WHERE id = ?";
    $sql_franchise 	= "SELECT * FROM franchises WHERE id = ?";
    $sql_event_type = "SELECT * FROM event_types WHERE id = ?";
    $sql_comments 	= "SELECT * FROM comments WHERE product_id = ? order by create_dt desc";
    
    if($stmt_event = mysqli_prepare($conn, $sql_event)){
        // Bind variables to the prepared statement as parameters
        // Set parameters
        $param_event_id = trim($_GET["id"]);

        mysqli_stmt_bind_param($stmt_event, "i", $param_event_id);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt_event)){
            $result_event = mysqli_stmt_get_result($stmt_event);
            if(mysqli_num_rows($result_event) == 1){        
                $row_event = mysqli_fetch_array($result_event, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $name 		         = $row_event["name"];
                $description         = $row_event["description"];
                $event_type_id       = $row_event["event_type_id"];
                $genre_id 		     = $row_event["genre_id"];
                $franchise_id 		 = $row_event["franchise_id"];
                $event_from_datetime = $row_event["event_from_datetime"];
                $event_to_datetime   = $row_event["event_to_datetime"];
                $event_status 	     = $row_event["event_status"];
                $price		 	     = $row_event["price"];

                // Retrieve Event Type Name using event.genre_id
                $stmt_event_type = mysqli_prepare($conn, $sql_event_type);
                mysqli_stmt_bind_param($stmt_event_type, "i", $genre_id);
                mysqli_stmt_execute($stmt_event_type);
                $result_event_type   = mysqli_stmt_get_result($stmt_event_type);
                $row_event_type      = mysqli_fetch_array($result_event_type, MYSQLI_ASSOC);
                $event_type_name     = $row_event_type["name"];
                
                // Retrieve Genre Name using event.genre_id
                $stmt_genre = mysqli_prepare($conn, $sql_genre);
                mysqli_stmt_bind_param($stmt_genre, "i", $genre_id);
                mysqli_stmt_execute($stmt_genre);
                $result_genre 	= mysqli_stmt_get_result($stmt_genre);
                $row_genre 	    = mysqli_fetch_array($result_genre, MYSQLI_ASSOC);
                $genre_name 	= $row_genre["name"];

                // Retrieve Franchise Name using franchise_id
                $stmt_franchise = mysqli_prepare($conn, $sql_franchise);
	            mysqli_stmt_bind_param($stmt_franchise, "i", $franchise_id);
                mysqli_stmt_execute($stmt_franchise);
	            $result_franchise 	= mysqli_stmt_get_result($stmt_franchise);
                $row_franchise 	= mysqli_fetch_array($result_franchise, MYSQLI_ASSOC);
                $franchise_name 	= $row_franchise["name"];

                // Retrieve Comments using Id
                $stmt_comments = mysqli_prepare($conn, $sql_comments);
	            mysqli_stmt_bind_param($stmt_comments, "i", $param_product_id);
                mysqli_stmt_execute($stmt_comments);
	            $result_comments 	= mysqli_stmt_get_result($stmt_comments);

            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: ./error.php");
                exit();
            } 
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    } else {
    	echo "Trouble with the mysql_prepare()";
    }
    
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
    <title>View Event</title>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">View Event</h1>

                    <div class="form-group">
                        <label>Name:</label>
                        <b><?php echo $name; ?></b>
                    </div>

                    <div class="form-group">
                        <label>Description:</label>
                        <b><?php echo $description; ?></b>
                    </div>

                    <div class="form-group">
                        <label>Event Type:</label>
                        <b><?php echo $event_type_name; ?></b>
                    </div>

                    <div class="form-group">
                        <label>Genre:</label>
                        <b><?php echo $genre_name; ?></b>
                    </div>

                    <div class="form-group">
                        <label>Franchise:</label>
                        <b><?php echo $franchise_name; ?></b>
                    </div>

                    <div class="form-group">
                        <label>Event From Date:</label>
                        <b><?php echo $event_from_datetime; ?></b>
                    </div>

                    <div class="form-group">
                        <label>Event To Date:</label>
                        <b><?php echo $event_to_datetime; ?></b>
                    </div>

                    <div class="form-group">
                        <label>Event Status:</label>
                        <b><?php echo $event_status; ?></b>
                    </div>

                    <div class="form-group">
                        <label>Price:</label>
                        <b><?php echo $price; ?></b>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mt-5 mb-3 clearfix">
                                <h2 class="pull-left">Event Comments</h2>
                                <?php echo '<a href="./createEventComment.php?id=' . $param_event_id . '" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Event Comment</a>'; ?>
                            </div>

                            <?php
                                if(mysqli_num_rows($result_comments) > 0) {
                                    echo '<table class="table table-bordered table-striped">';
                                        echo "<thead> <tr> <th> Comment </th> </tr> </thead>";
                                        echo "<tbody>";
                                        while($row = mysqli_fetch_array($result_comments)){
                                            echo "<tr>";
                                                echo "<td width=75%>" . $row['comment'] . "</td>";
                                                echo "<td width=25%>";
                                                    echo '<a href="./updateEventComment.php?id=' . $row['id'] .'" class="mr-3" title="Update Comment" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                                    echo '<a href="./deleteEventComment.php?id=' . $row['id'] .'&table=products'.'" title="Delete Comment" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                                echo "</td>";
                                            echo "</tr>";
                                        }
                                        echo "</tbody>";                            
                                    echo "</table>";
                                } else {
                                    echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                                }
                            ?>
                    </div>
                </div>        

                    <p><a href="./admin.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
