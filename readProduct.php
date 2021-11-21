<?php
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "config.php";

    // Prepare a select statements
    $sql_product 	= "SELECT * FROM products WHERE id = ?";
    $sql_genre 	= "SELECT * FROM genres WHERE id = ?";
    $sql_franchise 	= "SELECT * FROM franchises WHERE id = ?";
    $sql_comments 	= "SELECT * FROM comments WHERE product_id = ? order by create_dt desc";
    
    if($stmt_product = mysqli_prepare($conn, $sql_product)){
        // Bind variables to the prepared statement as parameters
        // Set parameters
        $param_product_id = trim($_GET["id"]);

        mysqli_stmt_bind_param($stmt_product, "i", $param_product_id);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt_product)){
            $result_product = mysqli_stmt_get_result($stmt_product);
            if(mysqli_num_rows($result_product) == 1){        
                $row_product = mysqli_fetch_array($result_product, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $name 			= $row_product["name"];
                $description 		= $row_product["description"];
                $genre_id 		= $row_product["genre_id"];
                $franchise_id 		= $row_product["franchise_id"];
                $release_dt 		= $row_product["release_dt"];
                $release_dt_status 	= $row_product["release_dt_status"];
                $price		 	= $row_product["price"];
                
                // Retrieve Genre Name from genre_id
                $stmt_genre = mysqli_prepare($conn, $sql_genre);
	        mysqli_stmt_bind_param($stmt_genre, "i", $genre_id);
                mysqli_stmt_execute($stmt_genre);
	        $result_genre 	= mysqli_stmt_get_result($stmt_genre);
                $row_genre 	= mysqli_fetch_array($result_genre, MYSQLI_ASSOC);
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
                header("location: error.php");
                exit();
            } 
            
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
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Product</title>
    
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
                    <h1 class="mt-5 mb-3">View Product</h1>

                    <div class="form-group">
                        <label>Name:</label>
                        <b><?php echo $name; ?></b>
                    </div>

                    <div class="form-group">
                        <label>Description:</label>
                        <b><?php echo $description; ?></b>
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
                        <label>Release Date:</label>
                        <b><?php echo $release_dt; ?></b>
                    </div>

                    <div class="form-group">
                        <label>Release Date Status:</label>
                        <b><?php echo $release_dt_status; ?></b>
                    </div>

                    <div class="form-group">
                        <label>Price:</label>
                        <b><?php echo $price; ?></b>
                    </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Product Comments</h2>
                        <?php echo '<a href="createProductComment.php?id=' . $param_product_id . '" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Product Comment</a>'; ?>
                    </div>
                    <?php
                        if(mysqli_num_rows($result_comments) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead> <tr> <th> Comment </th> </tr> </thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result_comments)){
                                    echo "<tr>";
                                        echo "<td width=75%>" . $row['comment'] . "</td>";
                                        echo "<td width=25%>";
                                            echo '<a href="updateProductComment.php?id=' . $row['id'] .'" class="mr-3" title="Update Comment" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="deleteProductComment.php?id=' . $row['id'] .'&table=products'.'" title="Delete Comment" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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
                    ?>
                </div>
            </div>        

                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
