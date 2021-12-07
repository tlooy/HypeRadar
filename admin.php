<?php 
	require_once("./header.php"); 
	require_once "./config.php";
?>	

	<div class="wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
				<div class="mt-5 mb-3 clearfix">
					<h2 class="pull-left">Genre List</h2>
					<a href="./createRecord.php?table=genres" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Genre</a>
				</div>
		<?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM genres";
                    if($result = mysqli_query($conn, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Genre Name</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="./readRecord.php?id='  . $row['id'] .'&table=genres'.'" class="mr-3" title="View Genre" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="./updateRecord.php?id='. $row['id'] .'&table=genres'.'" class="mr-3" title="Update Genre" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="./deleteRecord.php?id='. $row['id'] .'&table=genres'.'" title="Delete Genre" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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
 
                    ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Franchise List</h2>
                        <a href="./createRecord.php?table=franchises" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Franchise</a>
                    </div>
                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM franchises";
                    if($result = mysqli_query($conn, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Franchise Name</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="./readRecord.php?id='  . $row['id'] .'&table=franchises'.'" class="mr-3" title="View Franchise" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="./updateRecord.php?id='. $row['id'] .'&table=franchises'.'" class="mr-3" title="Update Franchise" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="./deleteRecord.php?id='. $row['id'] .'&table=franchises'.'" title="Delete Franchise" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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
 
                    ?>
                </div>
            </div>        

            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Event Type List</h2>
                        <a href="./createRecord.php?table=event_types" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Event Type</a>
                    </div>
                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM event_types";
                    if($result = mysqli_query($conn, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Event Type Name</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="./readRecord.php?id='  . $row['id'] .'&table=event_types'.'" class="mr-3" title="View Event Type" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="./updateRecord.php?id='. $row['id'] .'&table=event_types'.'" class="mr-3" title="Update Event Type" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="./deleteRecord.php?id='. $row['id'] .'&table=event_types'.'" title="Delete Event Type" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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
 
                    ?>
                </div>
            </div>        

            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Source List</h2>
                        <a href="./createRecord.php?table=sources" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Source</a>
                    </div>
                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM sources";
                    if($result = mysqli_query($conn, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Source Name</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="./readRecord.php?id='  . $row['id'] .'&table=sources'.'" class="mr-3" title="View Source" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="./updateRecord.php?id='. $row['id'] .'&table=sources'.'" class="mr-3" title="Update Source" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="./deleteRecord.php?id='. $row['id'] .'&table=sources'.'" title="Delete Source" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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
 
                    ?>
                </div>
            </div>        
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Product List</h2>
                        <a href="./createProduct.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Product</a>
                    </div>
                    <?php                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM products";
                    if($result = mysqli_query($conn, $sql)) {
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>"; 
	                                echo "<tr> <th> Product Name </th> </tr>";
                                echo "</thead>";
        
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td width=75%>" . $row['name'] . "</td>";
                                        echo "<td width=25%>";
                                            echo '<a href="./readProduct.php?id='  	  . $row['id'] .'" class="mr-3" title="View Product" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="./updateProduct.php?id='	  . $row['id'] .'" class="mr-3" title="Update Product" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="./createProductComment.php?id='. $row['id'] .'" class="mr-3" title="Add Product Comment" data-toggle="tooltip"><span class="fa fa-book"></span></a>';
                                            echo '<a href="./deleteRecord.php?id='	  . $row['id'] .'&table=products'.'" title="Delete Product" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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
                    ?>
                </div>
            </div>        

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

