<?php 
	require_once("./header.php"); 
	require_once "./config.php";
?>	

	<div class="wrapper">
		<div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Event List</h2>
                        <a href="./createEvent.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Event</a>
                    </div>
                    <?php                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM events";
                    if($result = mysqli_query($conn, $sql)) {
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>"; 
                                    echo "<tr> <th> Event Name </th> </tr>";
                                echo "</thead>";
        
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td width=75%>" . $row['name'] . "</td>";
                                        echo "<td width=25%>";
                                            echo '<a href="./readEvent.php?id='       . $row['id'] .'" class="mr-3" title="View Event" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="./updateEvent.php?id='   . $row['id'] .'" class="mr-3" title="Update Event" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="./createEventComment.php?id='. $row['id'] .'" class="mr-3" title="Add Event Comment" data-toggle="tooltip"><span class="fa fa-book"></span></a>';
                                            echo '<a href="./deleteRecord.php?id='    . $row['id'] .'&table=events'.'" title="Delete Event" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>"; 
                                                           
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
 
                    // Close connection
                    mysqli_close($conn);
                    ?>
                </div>
            </div>                
        </div>
    </div>
    <?php require_once("./footer.php"); ?>	

