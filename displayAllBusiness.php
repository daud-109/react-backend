<?php
/*This file will display the all of businesses
**a owner owns. So, this way they can select 
**which business they want to interact with.
*/
header('Content-Type: application/json');

//Talk to the team about setting the post if statement

session_start();

if (isset($_SESSION['owner_id'])) {

  //include the file to connect with mysql 
  require_once 'mysqlConn.php';

  //set the owner id
  $owner_id = $_SESSION['owner_id'];

  //Use the select to get the business id.
  $business_query = "SELECT * FROM business where owner_id = ?";
  $business_stmt = mysqli_stmt_init($conn);

  //if the business query failed
  if (!mysqli_stmt_prepare($business_stmt, $business_query)) {
    die("Fatal error the business select query failed");
  } else {
    //bind the variable to prepare the statement
    mysqli_stmt_bind_param($business_stmt, "i", $owner_id);

    //execute the statement
    mysqli_stmt_execute($business_stmt);

    //get result
    $result = mysqli_stmt_get_result($business_stmt);

    //if nothing is fetch than send error
    if (!$result) {
      die("Fatal error");
    } else {
      //Make array which get encode it into json
      //which will take patron and spreadsheet info
      $display_business = array();

      //help with increment
      $i = 0;

      //get all the business name for the owner
      while ($row = mysqli_fetch_assoc($result)) {

        //store the data inside the array
        $display_business[$i] = ["name" => $row['name'], "type" => $row['type'], "street" => $row['street'], "town" => $row['town'], "zip" => $row['zip'], "county" => $row['county']];

        $i++;
      }

      //encode the array into json formate
      $json = json_encode($display_business, JSON_PRETTY_PRINT);

      //now echo it 
      echo $json;

      //free the memory
      mysqli_stmt_free_result($business_stmt);

      //close the statement
      mysqli_stmt_close($business_stmt);
    }
  }

  //close the connection
  mysqli_close($conn);
} else {
  die("Please login");
}