<?php
/*This file will allow the patron to write review
**and rate the business only if they have been to that
**business.
*/
session_start();

if (isset($_SESSION['patron_id'])) {

  require_once "../mysqlConn.php";

  //declare the variable
  $mask_rating = $social_distance_rating =  $sanitize_rating = $comment = "";

  //declare post variable
  $mask_rating = htmlspecialchars($_POST['mask_rating']);
  $social_distance_rating = htmlspecialchars($_POST['social_distance_rating']);
  $sanitize_rating = htmlspecialchars($_POST['sanitize_rating']);
  $comment = htmlspecialchars($_POST['comment']);

  if (empty($mask_rating) || empty($social_distance_rating) || empty($sanitize_rating) || empty($comment)) {
    //display error if the value are empty
    die(http_response_code(409));
  } else {

    //get the patron and business id
    $patron_id = $_SESSION['patron_id'];
    $business_id = $_SESSION['business_id'];

    //insert statement
    $query = "INSERT INTO review (business_id, patron_id, mask_rating, social_distance_rating, sanitize_rating, comment) VALUES (?,?,?,?,?,?)";
    $stmt = mysqli_stmt_init($conn);

    //if the review insert query failed
    if (!mysqli_stmt_prepare($stmt, $query)) {
      //Fatal error for insert review query
      die(http_response_code(409));
    } else {
      //prepare the statement to bind the variable
      mysqli_stmt_bind_param($stmt, "iiiiis", $business_id, $patron_id, $mask_rating, $social_distance_rating, $sanitize_rating, $comment);
      
      //now check if the insert executed
      if (!mysqli_stmt_execute($stmt)) {
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
  
} else {
  //if they are not logged in
  die(http_response_code(404));
}
