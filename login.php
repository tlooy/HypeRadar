<?php
	require_once "./header.php"; 
	require_once "./config.php";

	$usernameoremail = $userpwd = "";
	$usernameemail_err = $pwd_err = "";

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$input_usernameoremail 	= trim($_POST["usernameoremail"]);
		$input_userpwd 		= trim($_POST["userpwd"]);
	
		if(empty($input_usernameoremail) || empty($input_userpwd)){
			$emptyinput_err = "Please enter values in all fields.";
		} 
	    
		if(!filter_var($input_usernameoremail, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z09@.\s]+$/")))){
	        	$usernameoremail_err = "Please enter a valid user name or email address.";
		} else{
			$usernameoremail = $input_usernameoremail;
		}

		if(!filter_var($input_userpwd, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
			$userpwd_err = "Please enter a valid password (numbers and letters only).";
		} else{
			$userpwd = $input_userpwd;
		}

		$sql  = "SELECT * FROM users WHERE userid = ? OR useremail = ?;";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("location: ./signup.php?errorlogin");
			exit();
		}

		mysqli_stmt_bind_param($stmt, "ss", $usernameoremail, $usernameoremail);
		mysqli_stmt_execute($stmt);
		$results = mysqli_stmt_get_result($stmt);
		$rows = mysqli_fetch_all($results, MYSQLI_ASSOC);
		$row = $rows[0];
		if (count($row) > 0) {
			$pwdhashed = $row["userpwd"];

			$checkPwd = password_verify($userpwd, $pwdhashed);
		
			if ($checkPwd === false) {
				header("location: ./login.php?errorlogin");
				exit();		
			}
			elseif  ($checkPwd === true) {
				session_start();
				$_SESSION["userid"] = $row["id"];
				header("location: ./index.php");
				exit();		
			}
		}
		else {
			header("location: ./login.php?wronglogin");
			exit();
		}
	}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                    <h2 class="mt-5">Log In</h2>
                    <p>Please fill this form and submit to log into your account on Hype Radar.</p>
                    <p>Note: This form is not functional yet...</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>User Id or eMail Address</label>
                            <input type="text" name="usernameoremail" class="form-control <?php echo (!empty($usernameemail_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $usernameoremail; ?>">
                            <span class="invalid-feedback"><?php echo $usernameoremail_err;?></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="userpwd" class="form-control <?php echo (!empty($userpwd_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $userpwd; ?>">
                            <span class="invalid-feedback"><?php echo $userpwd_err;?></span>
                        </div>
                        
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="./index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
<?php require_once("./footer.php"); ?>	

