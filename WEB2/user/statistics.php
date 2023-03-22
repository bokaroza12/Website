<?php
session_start();
  
  include("../includes/connection.php");
  include("../includes/functions.php");
  
  $user_data=check_login($con);
  
  if($_SERVER['REQUEST_METHOD']== "POST")
  {
    $search_result=$_POST['poi'];
    
    if(!empty($search_result))
    {
      $query = "SELECT * from poi where  list_name LIKE '%$search_result%'" ;
      
      $result=mysqli_query($con,$query);
      
      if($result)
      {
        $final=mysqli_fetch_assoc($result);
        $id= $final['list_id'];
        $lat= $final['list_coordinates_lat'];
        $lng= $final['list_coordinates_lng'];
        $name= $final['list_name'];
          //array of arrays containg the populartimes array
    $populartimes= array();
    array_push($populartimes,$final['pop_sun']);
    array_push($populartimes,$final['pop_mon']);
    array_push($populartimes,$final['pop_tue']);
    array_push($populartimes,$final['pop_wed']);
    array_push($populartimes,$final['pop_thu']);
    array_push($populartimes,$final['pop_fri']);
    array_push($populartimes,$final['pop_sat']);
    
    
    
        
        //find current popularity
        $query_popularity = "SELECT list_current_popularity from poi where  list_name LIKE '%$search_result%'" ;
        $result_popularity=mysqli_query($con,$query_popularity);
        
        
          $final_pop=mysqli_fetch_assoc($result_popularity);
          
          $popularity=$final_pop['list_current_popularity'];      
      }
    }
    
     $query_entry= "SELECT * from poi where list_coordinates_lat IS NOT NULL" ;
      
    $result_entry=mysqli_query($con,$query_entry);
      
      if($result_entry)
      {
        
        $lat_entry = array();
        $lng_entry = array();
        $name_entry= array();
        $id_entry = array();
         while($final_entry = mysqli_fetch_array($result_entry))
        {
          
          array_push($id_entry,$final_entry['list_id']);
          array_push($lat_entry,$final_entry['list_coordinates_lat']);
          //$lat_entry= $final_entry['list_coordinates_lat'];
          array_push($lng_entry, $final_entry['list_coordinates_lng']);
          
          array_push($name_entry, $final_entry['list_name']);
          
        }
    }
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
  	<title>Map|CDT</title>
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
      <h1> Points of Interest Search</h1>
      <form  method="post">
      <div id="map">
        <script>  
            var mymap = L.map('map').setView([38.24720, 21.73510], 13);
            
            <!--Adding tile layer -->
            L.tileLayer('https://api.maptiler.com/maps/streets/{z}/{x}/{y}.png?key=RxrjkArp0RlzAwmKCvzB',{
             attribution:'<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'
              
                }).addTo(mymap);
                <!--map.addLayer(tiles); -->
                var results= L.Control.geocoder().addTo(mymap);
          
                //gei aspyrakoooo moyyy <3 ti kanoyme sto pitiko maw 
                //kale pliktrologw polu kala se ayto to plhktrologio 
                //vgainw gia 2 leptakia
    
        </script>
      
        <script>      
        function check_time(){
          let today= new Date();
          
          //real_hour[day of the week, time of the day]
          const real_hour=[today.getDay(),today.getHours()];  
          console.log(real_hour);
          return real_hour;
        }
        
        function init(){
      
      
      var populartimes = <?php echo json_encode($populartimes,JSON_PRETTY_PRINT) ?>;
      
      console.log(populartimes);
    
      for (let i = 0; i < populartimes.length; i++) 
      {
        populartimes[i] = JSON.parse(populartimes[i]);
      }
    
      var time_check=check_time();
      if (time_check[1] <= 22)
      {
        var id= '<?= $id ?>';
        //iterator
        //coordinates for marker
        var search_lat= '<?= $lat ?>';
        var search_lng= '<?= $lng ?>';
        var popularity= '<?= $popularity ?>';
        console.log(search_lat,popularity);
        //evaluation for the next two hours.
        if(time_check != 0)
        {
          var time_now = populartimes[time_check[0] -1][time_check[1]];
          var time_one_hour = populartimes[time_check[0] -1 ][time_check[1] + 1];
          var time_two_hours = populartimes[time_check[0] -1 ][time_check[1] + 2];
          
          var estimation = (time_now + time_one_hour + time_two_hours) /3 ;
        }
        
        else
        {
          var time_now = populartimes[0][time_check[1]];
          var time_one_hour = populartimes[0][time_check[1] + 1];
          var time_two_hours = populartimes[0][time_check[1] + 2];
          
          estimation= (time_now + time_one_hour + time_two_hours) /3 ;
          
          //console.log(estimation);
        }
          //creating the message for the popup
          var p =" <p> Estimated people visiting per hour :  " + estimation + "</p>";
          var color_estimation= estimation/popularity;
          
          if(color_estimation<=0.32 || popularity==0)
          {
            var greenIcon = new L.Icon({
            iconUrl: '../images/marker-icon-green.png',
            shadowUrl: '../images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
            });

            //place marker with popup on poi 
            var marker = L.marker ([search_lat, search_lng], {icon: greenIcon});
            marker.addTo(mymap)
              .bindPopup("<h1> '<?=$name ?>'</h1>" + p);
      
          }
          
          else if (color_estimation>0.32 && color_estimation<=0.65)
          {
            var orangeIcon = new L.Icon({
            iconUrl: '../images/marker-icon-orange.png',
            shadowUrl: '../images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
            });
            
            //place marker with popup on poi 
            var marker = L.marker ([search_lat, search_lng], {icon: orangeIcon});
            marker.addTo(mymap)
              .bindPopup("<h1> '<?=$name ?>'</h1>" + p);
          }
          
          else 
          {
            var redIcon = new L.Icon({
            iconUrl: '../images/marker-icon-red.png',
            shadowUrl: '../images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
            });
            
            //place marker with popup on poi 
            var marker = L.marker ([search_lat, search_lng], {icon: redIcon});
            marker.addTo(mymap)
              .bindPopup("<h1> '<?=$name ?>'</h1>" + p);
          }
      }         
          
    }
          
          init();   
      </script> 
      </div>
      <div id="enter_position">
        <form method="post">
            <button onclick="getLocation()"type= "button">Enter your Position</button>
          <script  type="text/javascript">
      
        function getLocation(){
      if (!navigator.geolocation) {
        console.log("Your browser doesn't support geolocation feature!");
      } 
      else {
        navigator.geolocation.getCurrentPosition(getPosition);
      }
      var marker, circle, lat, long, accuracy;
          
            var violetIcon = new L.Icon({
                iconUrl: '../images/marker-icon-violet.png',
                shadowUrl: '../images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
                
      
      
      function marker_clicked(poi_id,poi_name,layer)
        {
           layer.bindPopup(poi_name)
            .on('click', function() { 
            
              var choice;
              if (confirm('Do you want to register a visit in ' + poi_name))
              {
                console.log('yes');
                
                var estimation = prompt("Submit the number of people inside the POI", "0");
                if (estimation != null) 
                {
                  //alert("You have entered " + tenure + " years" );
                  
                  var poi_array ={};
                  poi_array.id=0;
                  poi_array.value=poi_id;
                  poi_array.estimation= parseInt(estimation);
                  
                  $.ajax({
                    url:"visit_registration.php",
                    method: "post",
                    data:poi_array,
                    success: function(poi_id){
                      console.log(poi_id);
                    }
                  });
                }

                else 
                {
                  var poi_array ={};
                  poi_array.id=0;
                  poi_array.value=poi_id;
                  poi_array.estimation= 0;
                  
                  $.ajax({
                    url:"visit_registration.php",
                    method: "post",
                    data:poi_array,
                    success: function(poi_id){
                      console.log(poi_id);
                    }
                  });
                }
                
                
                
              }
              else
              {
                console.log('no');
              }
            
            })
          
        }
      
      
      function getPosition(position) 
      {

        // console.log(position)
        lat = position.coords.latitude;
        long = position.coords.longitude;
        
        //position.coords.accuracy
        accuracy = 3000;

        if (marker) {
        mymap.removeLayer(marker);
        }

        if (circle) {
        mymap.removeLayer(circle);
        }

        var ar_lat = <?php echo json_encode($lat_entry,JSON_PRETTY_PRINT) ?>;
        var ar_lng = <?php echo json_encode($lng_entry,JSON_PRETTY_PRINT) ?>;
        var ar_name = <?php echo json_encode($name_entry,JSON_PRETTY_PRINT) ?>;
        var ar_id = <?php echo json_encode($id_entry,JSON_PRETTY_PRINT) ?>;
        
        var tmp_ar_lat= [];
        var tmp_ar_lng =[];
        var tmp_name =[];
        var tmp_id =[];
        var tmp_count= 0;
        
        //prosthetoume 0.0001 gia na exoume 11.1 m
        
        
        // for to isolate pois near to users location
        for(let i=0 ; i<ar_lat.length; i++)
        {
        if( ar_lat[i]> lat - ((0.0001 * accuracy)/11.1) &&  ar_lat[i]< lat + ((0.0001 * accuracy)/11.1) && ar_lng[i]> long - ((0.0001 * accuracy)/11.1) &&  ar_lng[i]< long + ((0.0001 * accuracy)/11.1) )
        
        {         
          
          tmp_ar_lat[tmp_count]=ar_lat[i];
          tmp_ar_lng[tmp_count]=ar_lng[i];
          tmp_name[tmp_count]=ar_name[i];
          tmp_id[tmp_count]=ar_id[i];
          
          tmp_count ++;
        }

        }
        
        
        
        
        for (j=0; j<tmp_ar_lat.length; j++)
      {
        console.log(tmp_ar_lat[j],tmp_ar_lng[j]);
        
        
        
        marker = new L.marker([tmp_ar_lat[j],tmp_ar_lng[j]], {icon: violetIcon})
          .bindPopup( tmp_name[j])
          .addTo(mymap);
        
        marker_clicked(tmp_id[j],tmp_name[j], marker);
        
          
      }
        
        marker = L.marker([lat, long]);
        circle = L.circle([lat, long], { radius: accuracy });
        var featureGroup = L.featureGroup([marker, circle]).addTo(mymap);

        mymap.fitBounds(featureGroup.getBounds());

        console.log(
        "Your coordinate is: Lat: " +
          lat +
          " Long: " +
          long +
          " Accuracy: " +
          accuracy
        );
        
        console.log(ar_lat);
        
        
        
      }
        }

        
    </script>
      </form>
      </div>
      </form>
    
      <form id="poi" method="post">
      <div id="info_poi">
        <p>Μέσω πλαισίου ελεύθερης αναζήτησης μπορεί να αναζητήσει
        σημείο ενδιαφέροντος (POI) από τη βάση δεδομένων του συστήματος, και το οποίο να είναι εντός
        του ορατού εύρους του χάρτη για πιθανή επίσκεψη (εστιατόριο, καφέ, υπηρεσία κλπ).</p>
      </div>
      <div id="search_poi">
        <input id="text" type="text" name="poi" placeholder="Point Of Interest">
        <button id="button" type= "submit" name="submit"> SEARCH</button>
      </div>
    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      
    
     </form>
    </div>
   </div>
  </div>
 </body>
</html>