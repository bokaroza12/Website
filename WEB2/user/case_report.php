<?php 
	
	session_start();
  
	include("../includes/connection.php");
	include("../includes/functions.php");
  
	$user_data=check_login($con);
	$user_id=$user_data['id'];
	$query="SELECT max(day_of_test) as maxdate  FROM has_covid where user_id='$user_id' ";
	
	$result=mysqli_query($con,$query);
	$last=mysqli_fetch_assoc($result);
	$last_covid =$last['maxdate'];

?>

<!DOCTYPE html>

<html>
	<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width,initial-scale=1.0">
   <link rel="stylesheet"  href="../css/user.css">
  <!-- importing leaflet css -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <!-- importig js libraries (containing L Object)-->
  <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>
  <!-- adding geocode plugin , for searching -->
  <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
   <title>Report|CDT</title>
</head>
	<body>
		<div id="viewport">
  		<!-- Sidebar -->
  			<div id="sidebar">
    		<header>
      		<a href="#">Covid DT</a>
   			</header>
    		<ul class="nav">
		      <li><a href="statistics.php">Map</a></li>
		      <li><a href="case_report.php">Covid case report</a></li>
		      <li><a href="edit.php">Edit profile</a>
		      </li>
		      <li id="logout"><a href="../logout.php">Log out</a></li>
		    </ul>
		  </div>
  <!-- Content -->
  <div id="content">
    <nav class="navbar navbar-default">
        <ul class="navbar-right">
          <li id="profile">
            <img src="../images/user.png">
            <?php echo $user_data['username']; ?> 
          </li>
        </ul>
    </nav>
		<div class="container-fluid">
			 <form  method="post">
				<h1> Do you have COVID ? </h1>
			
				<label for="start"> When did you first found positive?</label>
				<input type="date" id="start" name="covid-start" value="2020-01-20" min="2020-01-20">	   
				<button type="button" type= "submit" name="submit" onclick="dateFunction()">I have covid!</button>
				<button type="button" name="noCovid" onclick="window.location='statistics.php'"> Cancel </button>
				
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
				<script>
				function dateFunction() 
				{
					let dateValue1 = document.querySelector("#start").value; 
					console.log(typeof(dateValue1));
					 <?php
						echo "var last ='$last_covid';";
				    ?>
					console.log(typeof(last));
					var date;
					//date = new Date(dateValue1).toString();
					var last_date= new Date(last);
					date = new Date(dateValue1);
				
					
					let difference = date.getTime() - last_date.getTime();
					let TotalDays = Math.ceil(difference / (1000 * 3600 * 24));
						
					
					console.log(TotalDays);
					if(TotalDays>14 || !last_date)
					{
						var date_array ={};
						date_array.id=0;
						date_array.value=dateValue1.toString();
						console.log(date_array.value);
									
						$.ajax({
							url:"covid_case.php",
							method: "post",
							data:date_array	,
							success: function(date_array){
							alert('You have register successfully!');}
						});
					}
					
					else 
					{
						alert('You have resgistered in the last 14 days you cannot register yet.');
					}
					
					
				}
				
				</script>

			 </form>	
		</div>
	</body>
</html>