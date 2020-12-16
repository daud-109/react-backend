<?php
/*This file will send notification to business.
**It will have auto date set up.
*/

//start the session
session_start();

//Check if the patron is logged in
if (isset($_SESSION['patron_id'])) {

  //include the file to connect with mysql 
  require_once '../mysqlConn.php';

  //set the patron id
  $patron_id = $_SESSION['patron_id'];

  //set the date for notification 
  $start_date = $end_date = $date_of_positive = "";

  //post variable
  $start_date = htmlspecialchars($_POST['start_date']);
  $end_date = htmlspecialchars($_POST['end_date']);
  $date_of_positive = htmlspecialchars($_POST['date_of_test']);

  //search for business id to store in notification table
  $query = "SELECT DISTINCT business_id FROM spreadsheet where patron_id = ? and sheet_date BETWEEN ? AND ? ORDER BY business_id";
  $stmt = mysqli_stmt_init($conn);

  //if the query does not run
  if (!mysqli_stmt_prepare($stmt, $query)) {
    //terminate the program
    die(http_response_code(409));
  } else {

    //bind the variable to prepare the statement
    mysqli_stmt_bind_param($stmt, "iss", $patron_id, $start_date, $end_date);

    //execute the data provide by the user and the sql stamens.
    mysqli_stmt_execute($stmt);

    //get result
    $result = mysqli_stmt_get_result($stmt);

    //now do the insert query
    $insert_query = "INSERT INTO notification (business_id, patron_id, positive_date) VALUES(?,?,?)";
    $insert_stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($insert_stmt, $insert_query)) {
      //terminate the program fatal error the notification insert query did not run
      die(http_response_code(409));
    } else {

      //so in order to make sure no duplication is inserted I have
      //to do select query and then look for the matches 
      $check_query = "SELECT * FROM notification WHERE business_id = ? AND patron_id = ? AND positive_date = ?";
      $check_stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($check_stmt, $check_query)) {
        //terminate
        die("Fatal error the select notification query failed");
      } else {

        $i = 0;
        //This will loop will get all the data need to store in the notification table
        while ($row = mysqli_fetch_assoc($result)) {

          //bind the variable
          mysqli_stmt_bind_param($check_stmt, "iis", $row['business_id'], $patron_id, $date_of_positive);

          //execute
          mysqli_stmt_execute($check_stmt);

          mysqli_stmt_store_result($check_stmt);

          $check_row = mysqli_stmt_num_rows($check_stmt);

          //check if the data was already submitted
          if ($check_row < 1) {
            //Provide the the statement to bind
            mysqli_stmt_bind_param($insert_stmt, "iis", $row['business_id'], $patron_id, $date_of_positive);

            //execute the delete statement
            if (!mysqli_stmt_execute($insert_stmt)) {
              die(http_response_code(409));
            } else {
              $flag = true;
              $business_id[$i] = $row['business_id'];
              $i++;
            }
          } else {
            $flag = false;
            die(http_response_code(409));
          }
        }
        if ($flag === true) {
          require_once "../business/sendemail/email.php";
          require_once "../business/sendemail/autoEmail.php";
          //print_r($business_id);
        } else {
          //this the same information
          die(http_response_code(409));
        }
      }
    }
  }
  
  //free the memory
  mysqli_stmt_free_result($check_stmt);

  //close the statement
  mysqli_stmt_close($check_stmt);

  //free the memory
  mysqli_stmt_free_result($stmt);

  //close the statement
  mysqli_stmt_close($stmt);

  //close the connection 
  mysqli_close($conn);
} else {
  //send error message Please Log-in
  die(http_response_code(409));
}
