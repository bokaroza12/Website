<!DOCTYPE html>
<html>
<head>
	<title>Covid DT</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">	
	<link rel="stylesheet"  href="css/signin.css">
	<img src="images/logo2.png" >
	<h2>Welcome, <br>To Covid Data Tracker</h2>
</head>
<body>
	<div class="container" id="container">
			<form action="includes/login.inc.php" method="post">
				<h1>Log in</h1>
					<input id="text" type="text" name="uid" placeholder="Username">
					<input id="text" type="password" name="pwd" placeholder="Password">
					<br>
					<button id="button" type="submit" name="submit">LOG IN</button>
			</form>

			 <form action="#">
				<button class="second" id="modalBtn">SIGN UP</button>
				<br>
					<?php
		if (isset($_GET["error"])) {
			if ($_GET["error"] == "emptyinput") {
				echo "<p>Fill in all fields!</p>";
			}
			elseif ($_GET["error"] == "invalidemail") {
				echo "<p>Choose a proper email!</p>";
			}
			elseif ($_GET["error"] == "passwordmatch") {
				echo "<p>Passwords don't match!</p>";
			}
			elseif ($_GET["error"] == "usernameoremailtaken") {
				echo "<p>Someone already uses this email or username!</p>";
			}
			elseif ($_GET["error"] == "none") {
				echo "<p>You have signed up!</p>";
			}
			elseif ($_GET["error"] == "wronglogin") {
				echo "<p>Please check your password and username and try again <p>";
			}
		}
		
	?>
			</form>		
	</div>

<div class="modal" id="simpleModal">
		<div class="modal-content">
			<div class="container" id="container">
				<span class="closeBtn">&times;</span>
				<form action ="includes/signup.inc.php" method="post">
				<h1>
				 Sign up
				</h1>
					<input id="text" type="text" name="uid" placeholder="Username">
					<input id="text" type="password" name="pwd" placeholder="Password" >
					<input id="text" type="password" name="pwdRepeat" placeholder="Confirm Password">
					<input id="text" class="emailInput" type="email" name="email" placeholder="Email">
					<button type="submit" name="submit" >
					 SIGN UP
					</button> 	

				</form>
			</div>				
		</div>
	</div>

			
	
 	<script src="js/main.js"></script>
</body>
</html>