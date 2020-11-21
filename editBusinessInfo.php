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

session_start([
  'cookie_lifetime' => 86400,
]);

if (isset($_SESSION['owner_id'])) {

  //include the file to connect with mysql 
  require_once 'mysqlConn.php';

  //set the owner id
  $owner_id = $_SESSION['owner_id'];

  //For some reason if the session id is not set
  if (empty($owner_id) || !(is_numeric($owner_id))) {
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
        $business_name = $row['name'];
        $business_type = $row['type'];
        $business_email = $row['email'];
        $business_phone = $row['phone'];
        $business_url = $row['url'];
      } else {
        //for some reason if we do not get the id 
        die("Fatal error no data of the id");
      }

      //free the memory
      mysqli_stmt_free_result($business_stmt);

      //close the statement
      mysqli_stmt_close($business_stmt);

      //query for the address
      $address_query = "SELECT * FROM business_address where business_id = ?";
      $address_stmt = mysqli_stmt_init($conn);

      //if the query does not run
      if (!mysqli_stmt_prepare($address_stmt, $address_query)) {
        die("Fatal error the address select query did not run");
      } else {

        //bind the variable to prepare the statement
        mysqli_stmt_bind_param($address_stmt, "i", $business_id);

        //execute the statement
        mysqli_stmt_execute($address_stmt);

        //prepare the result
        $address_result = mysqli_stmt_get_result($address_stmt);

        if (!$address_result) {
          die("Fatal error, no data");
        } else {

          //get the address table info
          $row = mysqli_fetch_assoc($address_result);

          //store the data inside the array
          $display_table = array("business_name" => $business_name, "business_type" => $business_type,     "business_email" => $business_email, "business_phone" => $business_phone, "business_url" => $business_url, "street" => $row['street'], "town" => $row['town'], "zip" => $row['zip'], "county" => $row['county']);

          //free the memory
          mysqli_stmt_free_result($address_stmt);

          //encode the array into json formate
          $json = json_encode($display_table, JSON_PRETTY_PRINT);

          //now echo it 
          echo $json;
        }
      }
    }
  }

  //close the connection
  mysqli_close($conn);
} else {
  die("Please login");
}
