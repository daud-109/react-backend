<?php
/*This file will check if the patron 
**have the authority to leave a review
*/

//start the session
session_start();

//Check if the patron is logged in
if (isset($_SESSION['patron_id'])) {

  //include the file to connect with mysql 
  require_once '../mysqlConn.php';

  //declare variable
  $id = "";

  //session data
  $id = $_SESSION['business_id'];

  //If any variable is empty send an error message. 
  if (empty($id)) {
    //Error message the json value send was empty
    die(http_response_code(409));
  } else {
    //set the patron id
    $patron_id = $_SESSION['patron_id'];

    //This query will check if the patron exit in spreadsheet
    $query = "SELECT COUNT(business.id) as count
    FROM (business 
    INNER JOIN spreadsheet
    ON business.id = spreadsheet.business_id)
    WHERE spreadsheet.patron_id = ? AND  business.id = ?";
    $stmt = mysqli_stmt_init($conn);

    //Check if the query failed
    if (!mysqli_stmt_prepare($stmt, $query)) {
      //Fatal error the business/spreadsheet query failed
      echo "fatal error with query";
      die(http_response_code(409));
    } else {

      //bind the variable to prepare the statement
      mysqli_stmt_bind_param($stmt, "ii", $patron_id, $id);

      //check if it executed
      if (mysqli_stmt_execute($stmt)) {

        //get the result
        $result = mysqli_stmt_get_result($stmt);

        //get the fetch array to set the data
        $row = mysqli_fetch_assoc($result);

        //check if the patron is allow leave review
        if ($row) {

          $review_query = "SELECT COUNT(?) as review_count
          FROM review 
          WHERE business_id = ?";
          $review_stmt = mysqli_stmt_init($conn);

          //check if the query is good
          if (!mysqli_stmt_prepare($review_stmt, $review_query)) {
            //Fatal error the business/spreadsheet query failed
            echo "Fatal error with review query";
            die(http_response_code(409));
          } else {

            //bind the statement
            mysqli_stmt_bind_param($review_stmt, "ii", $patron_id, $id);

            //check if code is not execute
            if (!mysqli_stmt_execute($review_stmt)) {
              echo "Not execute";
              //if the execute did not work
              die(http_response_code(404));
            } else {
              //get the result
              $check_result = mysqli_stmt_get_result($review_stmt);

              //get the fetch array to set the data
              $check_row = mysqli_fetch_assoc($check_result);

              //now check if the patron is allow to leave a review
              if ($check_row['review_count'] >= $row['count']) {
                echo "ur in here";
                //so if the check row has more count than 
                //they are not allow to leave a review
                die(http_response_code(409));
              }
            }
          }
        } else {
          //display error if user is not allow to submit review
          echo "error not allow to submit";
          die(http_response_code(404));
        }
      }
    }
  }

  //free the memory
  mysqli_stmt_free_result($review_stmt);

  //close the statement
  mysqli_stmt_close($review_stmt);

  //free the memory
  mysqli_stmt_free_result($stmt);

  //close the statement
  mysqli_stmt_close($stmt);

  //close the connection 
  mysqli_close($conn);
} else {
  echo "Need to login";
  //send error message
  die(http_response_code(404));
}
