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
  <title>Admin Edit</title>
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
        <?php
            if (isset($_SESSION['message']) && ($_SESSION['message']))
            {
                echo '<p class="notification">'.($_SESSION['message']).'</p>';
                unset($_SESSION['message']);
            }
        ?>
        <form method="POST" action="upload.php" enctype="multipart/form-data">
            <div class="upload-wrapper">
                <span class="file-name">Choose a file...</span>
                <input type="file" id="file-upload" name="uploadedFile">
            </div>
            <button type="submit" name="uploadBtn" value="Upload">Upload</button>
            <div>
              <br>
              <button type="submit" name="deleteBtn" onclick="DeleteAll()" value="Delete">Delete</button>
            </div>
             <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
            <script> 

              function DeleteAll()
              {
                var del_arr= {};
                del_arr.id=0;
                del_arr.value="TRUNCATE TABLE poi";

                $.ajax({
                  url:"delete_poi.php",
                  method:"post",
                  data:del_arr,
                  success: function(){
                    alert("You successfully deleted all the Points Of Interest");
                  }
                });
              }

            </script>
        </form>
    </div>
  </div>
</div>
</body>
</html>