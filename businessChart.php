<?php
/*This file will register the business. It
**will look for the owner id from the owner 
**table and put inside the business table as
**foreign key. If the id is not found send error.
**Talk to your team about how this file will work
**or how they want it to work.
*/
header('Content-Type: application/json');


//Talk to the team about setting the post if statement

session_start();

if (isset($_SESSION['owner_id'])) {

  //include the file to connect with mysql 
  require_once 'mysqlConn.php';

  //set the owner id
  $owner_id = $_SESSION['owner_id'];

  //For some reason if the session id is not set
  if (empty($owner_id)) {
    die("Fatal error");
  } else {

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

      //get the fetch array to set the business id
      if ($row = mysqli_fetch_assoc($result)) {
        $business_id = $row['id'];
      } else {
        //for some reason if we do not get the id 
        die("Fatal error no data of the id");
      }

      //free the memory
      mysqli_stmt_free_result($business_stmt);

      //close the statement
      mysqli_stmt_close($business_stmt);

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
        if (!mysqli_fetch_assoc($spreadsheet_result)) {
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
            $json = json_encode($array, JSON_PRETTY_PRINT);

            //now echo it 
            echo $json;
          }
        }
      }
    }
  }

  //close the connection
  mysqli_close($conn);
} else {
  die("Please login");
}
