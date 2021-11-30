<?php
	require_once "./header.php"; 
	require_once "./config.php";

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		session_start();
		session_unset();
		session_destroy();

		header("location: ./index.php"); 
	};
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>User Logout</title>
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
                    <h2 class="mt-5">User Logout</h2>
                    <p>Note: this form is not functional yet...</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="submit" class="btn btn-primary" value="Logout">
                        <a href="./index.php" class="btn btn-secondary ml-2">Cancel</a>

                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
<?php require_once("./footer.php"); ?>	

