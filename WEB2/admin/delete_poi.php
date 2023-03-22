<?php 
	session_start();
  
  	include("../includes/connection.php");
  	include("../includes/functions.php");

  	$user_data=check_login($con);
  	$del_ar=$_POST;
  	$del=$del_ar['value'];

  	//del=query
  	mysqli_query($con,$del);

?>