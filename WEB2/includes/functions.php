<?php

function check_login($con)
{
	
	if(isset($_SESSION['id']))
	{
	
		$id=$_SESSION['id'];
		$query="SELECT * from user where id = '$id' limit 1";

		$result=mysqli_query($con,$query);

		if($result && mysqli_num_rows($result) > 0)
		{
		
			$user_data=mysqli_fetch_assoc($result);
			return $user_data;

		}

	}
	
	//redirect to login
	header("location: ../login.php");
	die;
}

function check_admin_login($con)
{
	
	if(isset($_SESSION['id']))
	{
	
		$id=$_SESSION['id'];
		$query="SELECT * from admin where admin_id = '$id' limit 1";

		$result=mysqli_query($con,$query);

		if($result && mysqli_num_rows($result) > 0)
		{
		
			$user_data=mysqli_fetch_assoc($result);
			return $user_data;

		}

	}
	
	//redirect to login
	header("location: ../login.php");
	die;
}


function emptyInputEdit($username,$password,$passWordRepeat){
	$result;
	if (empty($username) || empty($password) || empty($passWordRepeat)) {
		$result = true;	
	}
	else{
		$result = false;
	}
	return $result;
}


function emptyInputSignup($username,$email,$password,$passWordRepeat){
	$result;
	if (empty($username) || empty($email) || empty($password) || empty($passWordRepeat)) {
		$result = true;	
	}
	else{
		$result = false;
	}
	return $result;
}


function invalidEmail($email){
	$result;
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$result = true;	
	}
	else{
		$result = false;
	}
	return $result;
}

function pwdMatch($password, $passWordRepeat) {
	$result;
	if ($password !== $passWordRepeat) {
		$result = true;	
	}
	else{
		$result = false;
	}
	return $result;
}

function uidExist($con, $username,$email) {
	$sql = "SELECT * FROM user WHERE username = ? OR email = ?;";
	$stmt = mysqli_stmt_init($con);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../login.php?error=stmtfailed");
		exit();
	}

	mysqli_stmt_bind_param($stmt, "ss", $username, $email);
	mysqli_stmt_execute($stmt);

	$resultData = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($resultData)){
		return $row;
	}
	else{
		$result = false;
		return $result;
	}

	mysqli_stmt_close($stmt);
}

function uidExistEdit($con, $id,$username_new,$password) {
	$sql = "SELECT * FROM user WHERE username = '$username_new' ;";
	$sql1 = "SELECT * FROM admin WHERE username = '$username_new' ;";
	//$stmt = mysqli_stmt_init($con);

	$final=mysqli_query($con,$sql);
	$final1=mysqli_query($con,$sql1);

	//$result = mysqli_fetch_assoc($final);
	if(mysqli_num_rows($final)!=0 || mysqli_num_rows($final1)!=0)
	{
		header("location: ../login.php?error=stmtfailed");
		return true;
	}

	else
	{
		$update="UPDATE user SET username='$username_new', password='$password' where id='$id'";


		mysqli_query($con,$update);
		header('location: edit.php');
		return false;
		
	}
}


function emptyInputLogin($username,$password){
	$result;
	if (empty($username) || empty($password)) {
		$result = true;	
	}
	else{
		$result = false;
	}
	return $result;
}

   
   
            
        