<?php
/*This file will allow the patron to write review
**and rate the business only if they have been to that
**business.
*/

//start the session
session_start();

//Check if the patron is logged in
if (isset($_SESSION['patron_id'])) {

  //include the file to connect with mysql 
  require_once '../mysqlConn.php';
  
  //declare the variable
  $mask_rating = $social_distance_rating =  $sanitize_rating = $comment = "";

  //set the patron id
  $patron_id = $_SESSION['patron_id'];
  

  //check if the patron allow to write the review
} else {
  //send error message
  die("Please Log-in");
}