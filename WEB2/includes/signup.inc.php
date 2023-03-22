<?php
session_start();
	
	include("connection.php");
	include("functions.php");
	
	if($_SERVER['REQUEST_METHOD']== "POST")
	{
		//something was posted
		$username=$_POST['uid'];
		$password=$_POST['pwd'];
		$email=$_POST['email'];
		$passWordRepeat = $_POST['pwdRepeat'];


	if (emptyInputSignup($username,$email,$password,$passWordRepeat) !== false) {
		header("location: ../login.php?error=emptyinput");
		exit();
	}

	if (invalidEmail($email) !== false) {
		header("location: ../login.php?error=invalidemail");
		exit();
	}

	if (pwdMatch($password, $passWordRepeat) !== false) {
		header("location: ../login.php?error=passwordmatch");
		exit();
	}

	if (uidExist($con, $username,$email) !== false) {
		header("location: ../login.php?error=usernameoremailtaken");
		exit();
	}

		if(!empty($username) && !empty($password) && !empty($email) && !is_numeric($usermane) && !is_numeric($email))
		{
			//save to server
			$query ="INSERT into user(username, password, email) values ('$username', '$password', '$email')" ;
			
			mysqli_query($con,$query);
			
			header("location: ../login.php?error=none");
			exit();
		}

		else
		{
			header("location: ../login.php");
		}
	
	}
?>