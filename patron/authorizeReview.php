<?php
/*This file will check if the patron 
**have the authority to leave a review
*/
//this will helps us read the json file
header("Access-Control-Allow-Origin: *");
$json = file_get_contents("php://input");
$data = json_decode($json, true);

//this will help us send json file
header('Content-Type: application/json');

//start the session
session_start();
//Check if the patron is logged in
if (isset($_SESSION['patron_id'])) {

  //include the file to connect with mysql 
  require_once '../mysqlConn.php';

  //Unset the business session to 
  //start new session
  if (isset($_SESSION['business_id'])) {
    unset($_SESSION['business_id']);
  }
  
  //declare variable
  $id = "";

  //json data
  $id = htmlspecialchars($data['id']);

  //If any variable is empty send an error message. 
  if (empty($id)) {
    //Error message
    die("The json value send was empty");
  } else {
    //set the patron id
    $patron_id = $_SESSION['patron_id'];

    //This query will check if the patron exit in spreadsheet
    $query = "SELECT DISTINCT business.id
    FROM (business 
    INNER JOIN spreadsheet
    ON business.id = spreadsheet.business_id)
    WHERE spreadsheet.patron_id = ? AND  business.id = ?";

    //initialize the statement
    $stmt = mysqli_stmt_init($conn);

    //Check if the query failed
    if (!mysqli_stmt_prepare($stmt, $query)) {
      die("Fatal error the business/spreadsheet query failed");
    } else {

      //bind the variable to prepare the statement
      mysqli_stmt_bind_param($stmt, "ii", $patron_id, $id);

      //check if it executed
      if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_store_result($stmt); //This is fetching data from the database

        //Number of result or rows.
        $row = mysqli_stmt_num_rows($stmt);

        //if the row is affected allow the patron to insert
        if ($row > 0) {
          echo "good";
          //Start session
          $_SESSION['business_id'] = $id; //business

        } else {
          echo "Something went wrong";
          //display error if user is not allow to submit review
          die(http_response_code(404));
        }
      }
    }
  }

  //free the memory
  mysqli_stmt_free_result($stmt);

  //close the statement
  mysqli_stmt_close($stmt);

  //close the connection 
  mysqli_close($conn);
  
} else {
  echo "Log-in";
  //send error message
  die(http_response_code(404));
}
