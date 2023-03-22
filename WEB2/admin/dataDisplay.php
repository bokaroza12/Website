<?php
session_start();
  
  include("../includes/connection.php");
  include("../includes/functions.php");
  
  $user_data=check_admin_login($con);

  //Total visits
  $query = "SELECT count(registeration_id) as id from visitregistaration";
  $final = mysqli_query($con,$query);
  $final2 = mysqli_fetch_assoc($final);

  //Total covid cases
  $query = "SELECT count(covid_case_id) as id2 from has_covid";
  $final3 = mysqli_query($con,$query);
  $final4 = mysqli_fetch_array($final3);

  //Total visits fron active cases
  $query = "SELECT count(registeration_id) as id3 
            from visitregistaration
            inner join has_covid on visitregistaration.user_id = has_covid.user_id";
  $final5 = mysqli_query($con,$query);
  $final6 = mysqli_fetch_array($final5);
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
  <!-- import chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title>Admin statistics</title>
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
     <p>Total number of visits recorded:
      <?php 
        echo $final2['id'];
      ?>
      </p>
      <p> Total number of cases reported:
      <?php 
        echo $final4['id2'];
      ?>
      </p>
      <p>Total number of visits from active cases:
      <?php 
        echo $final6['id3'];
      ?>
      </p>
    </div>
  </div>
</div>
</body>
</html>