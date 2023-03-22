<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
include("../includes/connection.php");
include("../includes/functions.php");
 
$message = ''; 
$import_file = 0;
if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Upload')
{
  if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK)
  {
    // get details of the uploaded file
    $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
    $fileName = $_FILES['uploadedFile']['name'];
    $fileSize = $_FILES['uploadedFile']['size'];
    $fileType = $_FILES['uploadedFile']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
 
    // sanitize file-name
    //$newFileName = md5(time() . $fileName) . '.' . $fileExtension;
 
    // check if file has one of the following extensions
    $allowedfileExtensions = array('json');
 
    if (in_array($fileExtension, $allowedfileExtensions))
    {
      // directory in which the uploaded file will be moved
      $uploadFileDir = '../uploaded_files/';
      $dest_path = $uploadFileDir . $fileName;

      // Check whether file exists before uploading it
      if (file_exists("../uploaded_files/" .$fileName)) 
      {
          $message = $fileName." already exists.";
      }        
      else 
      {
        if(move_uploaded_file($fileTmpPath, $dest_path)) 
        {
          $message ='File is successfully uploaded.';
          $import_file = 1;
        }
        else
        {
          $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
        }
      }
    }
    else
    {
      $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
    }
  }
  else
  {
    $message = 'There is some error in the file upload. Please check the following error.<br>';
    $message .= 'Error:' . $_FILES['uploadedFile']['error'];
  }
}

if($import_file == 1){
  //read the json file contents
  $jsondata = file_get_contents("../uploaded_files/" .$fileName);

  //convert json object to php associative array
  $data = json_decode($jsondata, JSON_OBJECT_AS_ARRAY);

  $sql = "INSERT INTO poi(list_id, list_name, list_address, list_types, list_coordinates_lat, list_coordinates_lng, list_rating, list_rating_n, list_current_popularity, pop_mon, pop_tue, pop_wed, pop_thu, pop_fri, pop_sat, pop_sun, list_time_spent) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";

  $stmt = mysqli_stmt_init($con);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
  header("location: dataEdit.php?error=stmtfailed");
  exit();
  }

  mysqli_stmt_bind_param($stmt, "ssssdddiissssssss", $list_id, $list_name, $list_address, $list_types, $list_coordinates_lat, $list_coordinates_lng, $list_rating, $list_rating_n, $list_current_popularity, $pop_mon, $pop_tue, $pop_wed, $pop_thu, $pop_fri, $pop_sat, $pop_sun, $list_time_spent);

  $inserted_rows = 0;
  foreach ($data as $data) {
    $list_id = $data['id'];
    $list_name = $data['name'];
    $list_address = $data['address'];
    $list_types = json_encode($data['types']);
    $list_coordinates_lat = $data['coordinates']['lat'];
    $list_coordinates_lng = $data['coordinates']['lng'];
    $list_rating = $data['rating'];
    $list_rating_n = $data['rating_n'];
    $list_current_popularity = $data['current_popularity'];
    $pop_mon = json_encode($data['populartimes'][0]['data']);
    $pop_tue = json_encode($data['populartimes'][1]['data']);
    $pop_wed = json_encode($data['populartimes'][2]['data']);
    $pop_thu = json_encode($data['populartimes'][3]['data']);
    $pop_fri = json_encode($data['populartimes'][4]['data']);
    $pop_sat = json_encode($data['populartimes'][5]['data']);
    $pop_sun = json_encode($data['populartimes'][6]['data']);
    $list_time_spent = json_encode($data['time_spent']);

    $stmt->execute();
    $inserted_rows ++;
  }

  if(count($data) == $inserted_rows){
    echo "success";
  }else{
    echo "error";
  }

  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}
$_SESSION['message'] = $message;
header("Location: dataEdit.php");
?>