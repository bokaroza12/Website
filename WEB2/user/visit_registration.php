<?php 

	session_start();
  
	include("../includes/connection.php");
	include("../includes/functions.php");
  
	$user_data=check_login($con);
	$user_id=$user_data['id'];
	$poi_id=$_POST;
	$poi=$poi_id['value'];
	$estimation=$poi_id['estimation'];
	
	if($estimation==0)
	{
		$query= "INSERT INTO visitRegistaration(user_id,poi_id) VALUES ('$user_id' , '$poi')";
		mysqli_query($con,$query);
		print_r($poi_id);
		print_r($poi);
	}
	
	else
	{
		$query= "INSERT INTO visitRegistaration(user_id,poi_id,estimation) VALUES ('$user_id' , '$poi', '$estimation')";
		mysqli_query($con,$query);
		print_r($poi_id);
		print_r($poi);
	}

?>