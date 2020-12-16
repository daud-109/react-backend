<?php
/*This file will check if the business got 
**notification from the patron.  
*/
header('Content-Type: application/json');

//start session
session_start();

//check if teh user is logged in
if (isset($_SESSION['owner_id'])) {
  //check if the user selected a business
  if (isset($_SESSION['business_id'])) {

    //include the file to connect with mysql 
    require_once '../mysqlConn.php';

    //set the business id
    $business_id = $_SESSION['business_id'];

    //check if the business got notification
    $query = "SELECT business_id, positive_date FROM notification WHERE business_id = ? 
              ORDER BY positive_Date DESC";
    $stmt = mysqli_stmt_init($conn);

    //if the business query is not setup properly
    if (!mysqli_stmt_prepare($stmt, $query)) {
      //display error
      //Fatal error the notification select query failed"
      die(http_response_code(409));
    } else {
      //bind the variable to prepare the statement
      mysqli_stmt_bind_param($stmt, "i", $business_id);

      //now check if the statement executed
      if (mysqli_stmt_execute($stmt)) {

        //get result
        $result = mysqli_stmt_get_result($stmt);

        //this array will hold date for the notification
        $display_notification_date = array();


        //this loop will get the date and store it in array
        $row = mysqli_fetch_assoc($result);

        //get the most recent alert
        $display_notification_date = ["positive_date" => $row['positive_date']];

        //encode the array into json formate
        $json = json_encode($display_notification_date, JSON_PRETTY_PRINT);

        //if no data get store
        if (!$json) {
          //Something went wrong with the json"
          die(http_response_code(409));
        } else {
          //now echo it 
          echo $json;
        }
      } else {
        //error message
        //Fatal error with the execution statement"
        die(http_response_code(409));
      }
    }

    //free the memory
    mysqli_stmt_free_result($stmt);

    //close the statement
    mysqli_stmt_close($stmt);

    //close the connection
    mysqli_close($conn);
  } else {
    //if the user did not select a business display the error
    die(http_response_code(409));
  }
} else {
  //if the business owner is not logged in
  die(http_response_code(404));
}
