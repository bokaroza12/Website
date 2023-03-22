<?php
   session_start();
   
   if( isset( $_SESSION['visit'] ) ) {
      $_SESSION['visit'] += 1;
   }else {
      $_SESSION['visit'] = 1;
   }
	
   echo  "You have visited this page ".  $_SESSION['visit'];  "in this session.";
?>