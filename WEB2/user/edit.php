<?php
session_start();
  
  include("../includes/connection.php");
  include("../includes/functions.php");
  
  $user_data=check_login($con);

  $id=$_SESSION['id'];
            //arithmos dilwsewn
            $query = "SELECT day_of_test as datev FROM has_covid WHERE user_id = '$id'";
            $final = mysqli_query($con,$query);
           
            $date= array();
            while ( $row = mysqli_fetch_assoc($final))
            {
                array_push($date,$row['datev']);
            }

            $query1 = "SELECT count(user_id) as id, visitregistaration.timestamp as timestp , poi.list_name as name FROM visitregistaration 
            INNER JOIN poi ON visitregistaration.poi_id=poi.list_id
            WHERE user_id = '$id'
            GROUP BY visitregistaration.poi_id";
            $final1 = mysqli_query($con,$query1);
            $result=mysqli_fetch_assoc($final1);
            $id=$result['id'];
            $time=$result['timestp'];
            $name = array();
            while($final_entry = mysqli_fetch_array($final1))
            {
                array_push($name,$final_entry['name']);
            }
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
   <title>Edit|CDT</title>
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

  <div class="container-fluid" id="edit">
      <h1> Edit profile </h1>
   <form action="Profile_update.php" method="post">
      <input id="text" type="text" name="uid" placeholder="Username"><br>
      <input id="text" type="password" name="pwd" placeholder="Password"><br>
      <input id="text" type="password" name="pwdRepeat" placeholder="Confirm Password"><br>
      <button type="submit" name="edit">Save</button><br>
      <?php
      if (isset($_GET["error"])) {
         if ($_GET["error"] == "emptyinput") {
            echo "<p>Fill in all fields!</p>";
         }
         elseif ($_GET["error"] == "passwordmatch") {
            echo "<p>Passwords don't match!</p>";
         }
         elseif ($_GET["error"] == "none") {
            echo "<p>You have signed up!</p>";
         }
         elseif ($_GET["error"] == "usernametaken") {
            echo "<p>Someone already uses this username!</p>";
         }
      }
   ?>
   </form>

   <form>
       <h1>History of case covid reports</h1>
       <p id ='reports'></p>

        <script> 
             var date = <?php echo json_encode($date,JSON_PRETTY_PRINT) ?>;
             document.getElementById('reports').innerHTML=date;
        </script>

   </form>

   <form>
       <h1>History of visits</h1>
       <?php
       echo " number of visit: $id    |   time of visit: $time  |   place of interest:   " ;
       echo implode(" , ",$name)
       ?>
   </form>
   </div>
</body>