<?php
/*This file will display the business review
**with access to review if the patron have the
**authorization.
*/

//this will helps us read the json file
header("Access-Control-Allow-Origin: *");
$json = file_get_contents("php://input");
$_POST = json_decode($json, true);

//this will help us send json file
header('Content-Type: application/json');

//file to connect to the database
require_once './mysqlConn.php';

//declare variable
session_start();

$id = $_SESSION['business_id'];


//If any variable is empty send an error message. 
if (empty($id)) {
  //Error message
  die(http_response_code(409));
} else {

  //query for review
  $query = 
  "SELECT CONCAT(patron.first_name, ' ', patron.last_name) AS patron_name, review.mask_rating, review.social_distance_rating, review.sanitize_rating, review.comment
  FROM ((review
  INNER JOIN business
  ON review.business_id = business.id)
  INNER JOIN patron
  on review.patron_id = patron.id)
  WHERE business.id = ?";

  $stmt = mysqli_stmt_init($conn);

  //Check if the query failed
  if (!mysqli_stmt_prepare($stmt, $query)) {
    die("Fatal error the business/review query failed");
  } else {

    //bind the variable to prepare the statement
    mysqli_stmt_bind_param($stmt, "i", $id);

    //check if the statement executed and
    //store the data
    if (mysqli_stmt_execute($stmt)) {

      //prepare the result
      $result = mysqli_stmt_get_result($stmt);

      //array to hold the review and business
      $business_review_array = array();

      //help with increment
      $i = 0;

      //use to loop to get the review data and the business name
      while ($row = mysqli_fetch_assoc($result)) {

        //this array will hold business name and review about the business
        $business_review_array[$i] = ["patron_name" => $row['patron_name'], "mask_rating" => $row['mask_rating'], "social_distance_rating" => $row['social_distance_rating'], "sanitize_rating" => $row["sanitize_rating"], "comment" => $row['comment']];

        //increment
        $i++;
      }

      //encode the array into json formate
      $json = json_encode($business_review_array, JSON_PRETTY_PRINT);

      //display the json
      echo $json;

      if (empty($json)) {
        die(http_response_code(409));
      }
    } else {
      echo "Fatal error with execute statement";
    }
  }

  //free the memory
  mysqli_stmt_free_result($stmt);

  //close the statement
  mysqli_stmt_close($stmt);

  //close the connection
  mysqli_close($conn);
}
