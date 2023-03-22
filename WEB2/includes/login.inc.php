<?php
session_start();
	
	include("connection.php");
	include("functions.php");
	
	if($_SERVER['REQUEST_METHOD']== "POST")
	{
		//something was posted
		$username=$_POST['uid'];
		$password=$_POST['pwd'];

		if (emptyInputLogin($username, $password) !== false) {
		header("location: ../login.php?error=emptyinput");
		exit();
		}

		if(!empty($username) && !empty($password)  && !is_numeric($username) )
		{
			//read to server
			$query ="SELECT * from user where username = '$username' limit 1" ;
			$query2 ="SELECT * from admin where username = '$username' limit 1" ;
			
			$result=mysqli_query($con,$query);
			$result2=mysqli_query($con,$query2);

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{
		
					$user_data=mysqli_fetch_assoc($result);
					if($user_data['password']== $password)
					{
						$_SESSION['id'] = $user_data['id'];

						$tmp_id=$user_data['id'];
						mysqli_query($con,$query);
 										

					
						header("location: ../user/statistics.php");
						die;

					}
				}
			}
			if($result2)
			{
				if($result2 && mysqli_num_rows($result2) > 0)
				{
					$user_data=mysqli_fetch_assoc($result2);
					if($user_data['password']== $password)
					{
						$_SESSION['id'] = $user_data['admin_id'];
						header("location: ../admin/adminpage.php");
						die;
					}
				}
			}
		header("location: ../login.php?error=wronglogin");
		exit();
			
		}

		else
		{
			header("location: ../login.php");
			exit();
		}
	
	}
?>