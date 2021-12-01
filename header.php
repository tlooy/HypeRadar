<!doctype html>
<html lang="en">


<head>
	<meta charset="UTF-8">
	<title>Dashboard</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/HypeRadar.css">
	<link rel="stylesheet" href="css/styles.css" />

	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

	<style>
	        .wrapper {
		        width: 600px;
			margin: 0 auto;
		}
		table tr td:last-child {
			width: 120px;
		}
		.content {
			overflow: auto;
			border: 3px solid #666;
		}
		.content div {
			padding: 10px;
		}
		.radar {
			float: left;
			width: 20%;
		}
		.title {
			float: center;
			width: 100%;
      		}

	</style>

	<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();   
		});
	</script>
</head>

<body>
	<nav>
		<div class="wrapper">
			<ul class="nav justify-content-end">
				<li class="nav-item">
					<a class="nav-link active" href="admin.php">Admin</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="queryProductsWithJSCalendar.php">User</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="profile.php">My Profile</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="signup.php">Sign Up</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="login.php">Sign In</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="logout.php">Sign Out</a>
				</li>
			</ul>
		
		</div>	
	</nav>


	<div class="content">
		<div class="radar">
			<img src="dbsfgid-6af8edba-90a5-45b0-aac4-7df242cf735e.gif" alt="Radar" width="150" height="150">
		</div>
		<div class="title">
			<h1>Hype Radar!</h1>
		</div>
	</div>


