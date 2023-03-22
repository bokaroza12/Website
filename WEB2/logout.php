<?php
session_start();
unset($_SESSION["id"]);
unset($_SESSION["uid"]);
header("Location: login.php");
?>