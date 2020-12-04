<?php
/*This file will send notification to business.
**It will have auto date set up.
*/

//check
if ($_SERVER['REQUEST_METHOD'] === "POST") {

  //start the session
  session_start();

  //Check if the patron is logged in
  if (isset($_SESSION['patron_id'])) {

    //include the file to connect with mysql 
    require_once '../mysqlConn.php';

    //set the patron id
    $patron_id = $_SESSION['patron_id'];

    //set the date for notification 
    $today_date = date("Y-m-d");

    //search for business id to store in notification table
    $query = "SELECT DISTINCT business_id FROM spreadsheet where patron_id = ? ORDER BY business_id";
    $stmt = mysqli_stmt_init($conn);

    //if the query does not run
    if (!mysqli_stmt_prepare($stmt, $query)) {
      //terminate the program
      die("Fatal error the spreadsheet select query did not run");
    } else {

      //bind the variable to prepare the statement
      mysqli_stmt_bind_param($stmt, "i", $patron_id);

      //execute the data provide by the user and the sql stamens.
      mysqli_stmt_execute($stmt);

      //get result
      $result = mysqli_stmt_get_result($stmt);

      //now do the insert query
      $insert_query = "INSERT INTO notification (business_id, patron_id, notification_date) VALUES(?,?,?)";
      $insert_stmt = mysqli_stmt_init($conn);

      if (!mysqli_stmt_prepare($insert_stmt, $insert_query)) {
        //terminate the program
        die("Fatal error the notification insert query did not run");
      } else {

        //so in order to make sure no duplication is inserted I have
        //to do select query and then look for the matches 
        $check_query = "SELECT * FROM notification WHERE business_id = ? AND patron_id = ? AND notification_date = ?";
        $check_stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($check_stmt, $check_query)) {
          //terminate
          die("Fatal error the select notification query failed");
        } else {


          while ($row = mysqli_fetch_assoc($result)) {

            //bind the variable
            mysqli_stmt_bind_param($check_stmt, "iis", $row['business_id'], $patron_id, $today_date);

            //execute
            mysqli_stmt_execute($check_stmt);

            mysqli_stmt_store_result($check_stmt);

            $check_row = mysqli_stmt_num_rows($check_stmt);

            if ($check_row < 1) {
              //Provide the the statement to bind
              mysqli_stmt_bind_param($insert_stmt, "iis", $row['business_id'], $patron_id, $today_date);

              //execute the delete statement
              if (!mysqli_stmt_execute($insert_stmt)) {
                echo "It did not executed the insert. ";
              } else {
                echo "Notification send ";
              }
            } else {
              echo "Same\n";
            }
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
    //send error message
    die("Please Log-in");
  }
} else {

  //send error because user try to get inside the file without clicking on the submit button
  die(http_response_code(404));
}
