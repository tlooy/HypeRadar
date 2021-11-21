<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
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
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <?php require_once("header.php"); ?>	
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Genre List</h2>
                        <a href="createRecord.php?table=genres" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Genre</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
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
                                            echo '<a href="readRecord.php?id='  . $row['id'] .'&table=genres'.'" class="mr-3" title="View Genre" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="updateRecord.php?id='. $row['id'] .'&table=genres'.'" class="mr-3" title="Update Genre" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="deleteRecord.php?id='. $row['id'] .'&table=genres'.'" title="Delete Genre" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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
                    mysqli_close($link);
                    ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Franchise List</h2>
                        <a href="createRecord.php?table=franchises" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Franchise</a>
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
                                            echo '<a href="readRecord.php?id='  . $row['id'] .'&table=franchises'.'" class="mr-3" title="View Franchise" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="updateRecord.php?id='. $row['id'] .'&table=franchises'.'" class="mr-3" title="Update Franchise" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="deleteRecord.php?id='. $row['id'] .'&table=franchises'.'" title="Delete Franchise" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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
                    mysqli_close($link);
                    ?>
                </div>
            </div>        

            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Source List</h2>
                        <a href="createRecord.php?table=sources" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Source</a>
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
                                            echo '<a href="readRecord.php?id='  . $row['id'] .'&table=sources'.'" class="mr-3" title="View Source" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="updateRecord.php?id='. $row['id'] .'&table=sources'.'" class="mr-3" title="Update Source" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="deleteRecord.php?id='. $row['id'] .'&table=sources'.'" title="Delete Source" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Product List</h2>
                        <a href="createProduct.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Product</a>
                    </div>
                    <?php                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM products";
                    if($result = mysqli_query($conn, $sql)){
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
                                            echo '<a href="readProduct.php?id='  	  . $row['id'] .'" class="mr-3" title="View Product" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="updateProduct.php?id='	  . $row['id'] .'" class="mr-3" title="Update Product" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="createProductComment.php?id='. $row['id'] .'" class="mr-3" title="Add Product Comment" data-toggle="tooltip"><span class="fa fa-book"></span></a>';
                                            echo '<a href="deleteRecord.php?id='	  . $row['id'] .'&table=products'.'" title="Delete Product" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        
        </div>
    </div>
    <?php require_once("footer.php"); ?>	
</body>
</html>
