<?php
session_start();
include('thewall_process.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP & MYSQL - The Wall Login</title>
	<style type="text/css">
		* {
			margin: 0;
			padding: 0;
			font-family: sans-serif;
		}
		h2 {
			padding-bottom: 20px;
		}
		form {
			width: 525px;
			padding: 10px;
			border: 1px solid gray;
			margin: 0 50px 70px 50px;
		}
		label {
			display: inline-block;
			padding: 5px 0;
			width: 150px;
			text-align: right;
		}
		input {
			display: inline-block;
			padding: 5px 0;
			width: 150px;
		}
		input[type="submit"] {
			margin: 5px 0 5px 85px;
			border: 1px solid black;
			border-radius: 5px;
			font-weight: 700;
			border: 2px solid black;
		}
		#nonheader {
			padding: 50px;
		}
		.error {
			color: red;
		}
		.success {
			color: green;
		}
	</style>
</head>
<body>
	<div id="wrapper">
		<?php
			add_header_region();
		?>
		<div id="nonheader">
			<?php
				if(isset($_SESSION['errors'])) {
					foreach ($_SESSION['errors'] as $error) {
						echo "<p class='error'>{$error} </p>";
					}
					unset($_SESSION['errors']);
				}
				if(isset($_SESSION['success_message'])) {
					echo "<p class='success'>{$_SESSION['success_message']} </p>";
					unset($_SESSION['success_message']);
				}
			?>
			<h2>Register</h2>
			<form action="thewall_process.php" method="post">
				<input type="hidden" name="action" value="register"><br>
				<label for="first_name">First name:</label> 
				<input type="text" name="first_name"><br>
				<label for="last_name">Last name:</label> 
				<input type="text" name="last_name"><br>
				<label for="email">Email address:</label> 
				<input type="text" name="email"><br>
				<label for="password">Password:</label> 
				<input type="text" name="password"><br>
				<label for="confirm_password">Confirm Password:</label> 
				<input type="text" name="confirm_password"><br>
				<input type="submit" value="register">
			</form>
			<h2>Login</h2>
			<form action="thewall_process.php" method="post">
				<input type="hidden" name="action" value="login"><br>
				<label for="email">Email address:</label> 
				<input type="text" name="email"><br>
				<label for="password">Password:</label> 
				<input type="password" name="password"><br>
				<input type="submit" value="login">
			</form>
		</div>
	</div>
</body>
</html>