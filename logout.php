<?php 
//this will logout the user when the user
//click the logout button.
//require_once 'function.php';

session_start();

if (isset($_SESSION['owner_id']) || $_SESSION['owner_email']){
  session_unset();
  session_destroy();
}
else{
  die("You were never login");
}
?>