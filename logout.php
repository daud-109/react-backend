<?php 
//this will logout the user when the user
//click the logout button.
require_once 'function.php';

session_start();

if (isset($_SESSION['username'])){
  
  destroySession();

}
else{
  die("You were never login");
}
?>