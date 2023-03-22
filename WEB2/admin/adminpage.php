<?php
session_start();
  
  include("../includes/connection.php");
  include("../includes/functions.php");
  
  $user_data=check_admin_login($con);
  
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
	<title>Admin Home</title>
</head>
<body>
<div id="viewport">
  <!-- Sidebar -->
  <div id="sidebar">
    <header>
      <a href="adminpage.php">Covid DT</a>
    </header>
    <ul class="nav">
      <li><a href="dataEdit.php">Data processing</a></li>
      <li><a href="dataDisplay.php">Statistics</a></li>
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
      <h1>Welcome to Admin main page</h1>
      <img src="../images/logo2.png"  >

      
    </div>
  </div>
</div>
</body>
</html>