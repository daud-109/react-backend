<?php
/*This file will send notification to business.
**It will have auto date set up.
*/

//check
if ($_SERVER['REQUEST_METHOD'] === "POST") {

  //start the session
  session_start();

  //Check if the patron is logged in
  if (isset($_SESSION['patron_id'])) {

    //include the file to connect with mysql 
    require_once '../mysqlConn.php';

    //set the patron id
    $patron_id = $_SESSION['patron_id'];

    //set the date for notification 
    $today_date = date("Y-m-d");
    //search for business id to store in notification table
  } else {
    //send error message
    die("Please Log-in");
  }
} else {

  //send error because user try to get inside the file without clicking on the submit button
  die(http_response_code(404));
}
