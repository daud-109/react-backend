<?php
/*This file will select a business and start a session for that business.
**It good to test if the id exits in the database before starting the session.
**id is unique value so it will be easy to identify the business.
**Talk to your team if they want send data as json or post (most likely post).
**if using post make sure the user cannot edit the info
*/

header("Access-Control-Allow-Origin: *"); //see if you can remove the * star sign and add json application/json
$json = file_get_contents("php://input"); //you can remove this line and see what happen
$data = json_decode($json, true); //rue make as an associated array 

//Start session
session_start();

//include the file to connect with mysql 
require_once './mysqlConn.php';
require_once './function.php';

//Unset the business session to 
//start new session
if (isset($_SESSION['business_id'])) {
  unset($_SESSION['business_id']);
}

//declare the variable
$id = "";

//Post variables 
$id = htmlspecialchars($data['id']);

//check if the post is empty
if (empty($id)) {
  die("Fatal error, id was not enter");
} else {

  //check if the id exits
  $query = "SELECT * FROM business where id = ?";
  $stmt = mysqli_stmt_init($conn);

  //if the query does not run
  if (!mysqli_stmt_prepare($stmt, $query)) {
    die("Fatal error with query");
  } else {
    //prepare the statement by binding variable
    mysqli_stmt_bind_param($stmt, "i", $id);

    //execute the statement
    mysqli_stmt_execute($stmt);

    //get the result to check the data
    $result = mysqli_stmt_get_result($stmt);

    //now check if the id match is affected
    if ($row = mysqli_fetch_assoc($result)) {

      if ($id == $row['id']) {
        //store the session 
        //session_start();
        $_SESSION['business_id'] = $row['id'];
      } else {

        //if the id did not match fatal error when matching the id
        die(http_response_code(409));
      }
    } else {

      //if no data is fetch no data was fetch
      die(http_response_code(409));
    }
  }
}

//free the memory
mysqli_stmt_free_result($stmt);

//close the statement
mysqli_stmt_close($stmt);

//close the connection
mysqli_close($conn);
