<?php 

	session_start();
  
	include("../includes/connection.php");
	include("../includes/functions.php");
	
	$user_data=check_login($con);
	$user_id=$user_data['id'];
	
	
	$date_ajax=$_POST;
	$date=$date_ajax['value'];
	
	if($date)
	{
		$query= "INSERT INTO has_covid(user_id,day_of_Test) VALUES ('$user_id' , '$date')";
		mysqli_query($con,$query);
	
	}
	
?>