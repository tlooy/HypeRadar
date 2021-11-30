<?php
// Include config file
require_once("./header.php"); 
require_once "./config.php";
 
// Define variables and initialize with empty values
$username = $userid = $useremail = $userpwd = $validatingpwd = "";
$emptyinput_err = $username_err = $userid_err = $useremail_err = $pwd_err = $vadidatingpwd_err = $duplicateidoremail_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$input_username 	= trim($_POST["username"]);
	$input_userid 		= trim($_POST["userid"]);
	$input_useremail	= trim($_POST["useremail"]);
	$input_userpwd 	= trim($_POST["userpwd"]);
	$input_validatingpwd 	= trim($_POST["validatingpwd"]);

	if(empty($input_username) || empty($input_userid) || empty($input_useremail) || empty($input_userpwd) || empty($input_validatingpwd)){
		$emptyinput_err = "Please enter values in all fields.";
	} 
    
	if(!filter_var($input_username, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        	$username_err = "Please enter a valid user name (letters only).";
	} else{
		$username = $input_username;
	}
    
	if(!filter_var($input_userid, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
		$userid_err = "Please enter a valid user id (numbers and letters only).";
	} else{
		$userid = $input_userid;
	}

	if(!filter_var($input_useremail, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9@.\s]+$/")))){
		$useremail_err = "Please enter a valid email address.";
	} else{
		$useremail = $input_useremail;
	}

	if(!filter_var($input_userpwd, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
		$userpwd_err = "Please enter a valid password (numbers and letters only).";
	} else{
		$userpwd = $input_userpwd;
	}

	if($input_userpwd != $input_validatingpwd){
		$validatingpwd_err = "Passwords do not match.";
		header("location: ./signup.php?pwdvalidationerror");
		exit();
	}

	$sql  = "SELECT * FROM users WHERE userid = ? or useremail = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ./signup.php?stmtfailed");
		exit();
	}
    
	mysqli_stmt_bind_param($stmt, "ss", $userid, $useremail);
	mysqli_stmt_execute($stmt);
	$results = mysqli_stmt_get_result($stmt);
	$rows = mysqli_fetch_assoc($results);
	$count = mysqli_num_rows($results);
	if ($count > 0) {
		echo "User ID and or eMail already used.";
		$duplicateidoremail_err = "User ID and or eMail already used.";
		header("location: ./signup.php?duperror");
		exit();	
	};

	// Check input errors before inserting in database
	if(empty($username_err) && empty($userid_err) && empty($useremail_err) && empty($userpwd_err) && empty($validating_err) && empty($duplicateidoremail_err)) {
		// Prepare an insert statement
		$sql = "INSERT INTO users (username, userid, useremail, userpwd) VALUES (?, ?, ?, ?)";
         
		if($stmt = mysqli_prepare($conn, $sql)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "ssss", $username, $userid, $useremail, $userpwd);
            
			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				// Records created successfully. Redirect to landing page
				header("location: ./index.php");
				exit();
			} else{
				echo "Oops! Something went wrong. Please try again later.";
			}
		}
         
		// Close statement
		mysqli_stmt_close($stmt);
	}
    
	// Close connection
	mysqli_close($conn);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Create Account</title>
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
                    <h2 class="mt-5">Create Account</h2>
                    <p>Please fill this form and submit to create a Hype Radar User Account.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err;?></span>
                        </div>
                        
                        <div class="form-group">
                            <label>User Identifier for Login</label>
                            <input type="text" name="userid" class="form-control <?php echo (!empty($userid_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $userid; ?>">
                            <span class="invalid-feedback"><?php echo $userid_err;?></span>

                        <div class="form-group">
                            <label>eMail Address</label>
                            <input type="text" name="useremail" class="form-control <?php echo (!empty($useremail_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $useremail; ?>">
                            <span class="invalid-feedback"><?php echo $useremail_err;?></span>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="userpwd" class="form-control <?php echo (!empty($userpwd_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $userpwd; ?>">
                            <span class="invalid-feedback"><?php echo $userpwd_err;?></span>

                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="validatingpwd" class="form-control <?php echo (!empty($validatingpwd)) ? 'is-invalid' : ''; ?>" value="<?php echo $validatingpwd; ?>">
                            <span class="invalid-feedback"><?php echo $validatingpwd_err;?></span>

                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="./index.php" class="btn btn-secondary ml-2">Cancel</a>

                    </form>
                </div>
            </div>        
        </div>
    </div>
    
    <?php
	    if (isset($_GET["duperror"])) {
    		echo ("Duplicate User ID or eMail address.");
	    }

	    if (isset($_GET["pwdvalidationerror"])) {
    		echo ("Passwords do not match.");
	    }

    ?>

</body>
</html>
<?php require_once("./footer.php"); ?>	

