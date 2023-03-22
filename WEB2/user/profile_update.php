<?php
 
 session_start();

 include ("../includes/connection.php");
 include ("../includes/functions.php");

 $user_data=check_login($con);
 $id=$user_data['id'];

 if(isset($_POST['edit']))
 {
    $id=$_SESSION['id'];
    $username_new=$_POST['uid'];
    $password=$_POST['pwd'];
    $passWordRepeat=$_POST['pwdRepeat'];

    if (emptyInputEdit($username_new,$password,$passWordRepeat) !== false) {
        header("location: edit.php?error=emptyinput");
        exit();
    }
    if (pwdMatch($password, $passWordRepeat) !== false) {
        header("location: edit.php?error=passwordmatch");
        exit();
    }
    if (uidExistEdit($con,$id,$username_new,$password) != false) {
        header("location: edit.php?error=usernametaken");
        exit();            
    }

 }
?>