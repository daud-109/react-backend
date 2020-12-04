<?php
/*This file will get a business info base on 
**the selected business and then send put inside
**the email.  
*/

//start session
session_start();

//check if teh user is logged in
if (isset($_SESSION['owner_id'])) {

  //check if the user selected a business
  if (isset($_SESSION['business_id'])) {

    //include the file to connect with mysql 
    require_once '../../mysqlConn.php';

    //set the business id
    $business_id = $_SESSION['business_id'];

    //Use the select to get business information
    $query = "SELECT * FROM business where id = ?";
    $stmt = mysqli_stmt_init($conn);

    //if the business query failed
    if (!mysqli_stmt_prepare($stmt, $query)) {
      //display error
      die("Fatal error the business select query failed");
    } else {
      //bind the variable to prepare the statement
      mysqli_stmt_bind_param($stmt, "i", $business_id);

      //execute the statement
      if (mysqli_stmt_execute($stmt)) {

        //get result
        $result = mysqli_stmt_get_result($stmt);

        //get the fetch array to set the data
        $business_row = mysqli_fetch_assoc($result);
      } else {
        //error for execute
        echo "Something went wrong with execute ";
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
    echo "Select a business";
  }
} else {
  echo "Log in please";
  //if the business owner is not logged in
  die(http_response_code(404));
}
