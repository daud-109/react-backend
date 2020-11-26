<?php
/*This file will display the patron info base on
**selected business. It will send info as json to 
**the front-end. 
*/
header('Content-Type: application/json');

//start the session
session_start();

//Check if the user is login
if (isset($_SESSION['owner_id'])) {
 
  //Check if the user selected a business
  if (isset($_SESSION['business_id'])) {
    //include the file to connect with mysql 
    require_once 'mysqlConn.php';

    //set the owner id
    $business_id = $_SESSION['business_id'];

    //query for the spreadsheet
    $spreadsheet_query = "SELECT * FROM spreadsheet where business_id = ?";
    $spreadsheet_stmt = mysqli_stmt_init($conn);

    //if the query does not run
    if (!mysqli_stmt_prepare($spreadsheet_stmt, $spreadsheet_query)) {
      die("Fatal error the spreadsheet select query did not run");
    } else {

      //bind the variable to prepare the statement
      mysqli_stmt_bind_param($spreadsheet_stmt, "i", $business_id);

      //execute the statement
      mysqli_stmt_execute($spreadsheet_stmt);

      //prepare the result
      $spreadsheet_result = mysqli_stmt_get_result($spreadsheet_stmt);

      //if nothing is fetch than send error
      if (!$spreadsheet_result) {
        die("Fatal error, no data");
      } else {

        //Now set up the patron query
        $patron_query = "SELECT * FROM patron where id = ?";
        $patron_stmt = mysqli_stmt_init($conn);

        //if the patron query fails 
        if (!mysqli_stmt_prepare($patron_stmt, $patron_query)) {
          die("Fatal error for the patron query");
        } else {

          //Make array which get encode it into json
          //which will take patron and spreadsheet info
          $display_table = array();

          //help with increment
          $i = 0;

          //This loop will help us get the data to display on the business main page 
          while ($spreadsheet_row = mysqli_fetch_assoc($spreadsheet_result)) {

            //prepare statement to get the patron id
            mysqli_stmt_bind_param($patron_stmt, "i", $spreadsheet_row['patron_id']);

            //execute the statement
            mysqli_stmt_execute($patron_stmt);

            //prepare the statement for the result
            $patron_result = mysqli_stmt_get_result($patron_stmt);

            //get the associated array for patron
            $patron_row = mysqli_fetch_assoc($patron_result);

            //store the data inside the array
            $display_table[$i] = ["first_name" => $patron_row['first_name'], "last_name" => $patron_row['last_name'], "email" => $patron_row['email'], "temperature" => $spreadsheet_row['temperature'], "sheet_date" => $spreadsheet_row['sheet_date']];

            //increment
            $i++;
          }

          //free the memory
          mysqli_stmt_free_result($spreadsheet_stmt);
          mysqli_stmt_free_result($patron_stmt);

          //close the statement
          mysqli_stmt_close($spreadsheet_stmt);
          mysqli_stmt_close($patron_stmt);

          //encode the array into json formate
          $json = json_encode($display_table, JSON_PRETTY_PRINT);

          //now echo it 
          echo $json;
        }
      }
    }

    //close the connection
    mysqli_close($conn);
  } else {
    die("Please select a business");
  }
} else {
  die("Please login");
}
