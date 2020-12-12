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
$id = "";

//json data
$id = htmlspecialchars($_POST['id']);


//If any variable is empty send an error message. 
if (empty($id)) {
  //Error message
  die("Please enter all the value");
} else {

  //query to search for business info and review
  $query = "SELECT business.name, review.mask_rating, review.social_distance_rating, review.sanitize_rating, review.comment 
  FROM (business
  INNER JOIN review
  ON business.id = review.business_id)
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
    if(mysqli_stmt_execute($stmt)){

      //prepare the result
      $result = mysqli_stmt_get_result($stmt);

      //array to hold the review and business
      $business_review_array = array();

      //help with increment
      $i = 0;

      //use to loop to get the review data and the business name
      while ($row = mysqli_fetch_assoc($result)) {

        //this array will hold business name and review about the business
        $business_review_array[$i] = ["name" => $row['name'], "mask_rating" => $row['mask_rating'], "social_distance_rating" => $row['social_distance_rating'], "sanitize_rating" => $row["sanitize_rating"],"comment" => $row['comment']];
        //increment
        $i++;
      }
      
      //encode the array into json formate
      $json = json_encode($business_review_array, JSON_PRETTY_PRINT);
      
      //display the json
      echo $json;

      if(empty($json)){
        echo "No data";
      }

    }else{ 
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
