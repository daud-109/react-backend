<?php
/*This file will modify business info that 
**user want to modify.
*/

//start session
session_start();

//check if the user is logged in
if (isset($_SESSION['owner_id'])) {

  //check if the user selected a business
  if (isset($_SESSION['business_id'])) {
    //include the file to connect with mysql 
    require_once 'mysqlConn.php';
    require_once 'function.php';

    //set the business id
    $id = $_SESSION['business_id'];

    //declare variable for business
    $name = $type = $email = $phone = $description = $street = $town = $zip  = $county = "";

    //Post variable here and fill in the post variable name
    $name = htmlspecialchars($_POST['']);
    $type = htmlspecialchars($_POST['']);
    $email = htmlspecialchars($_POST['']);
    $phone = htmlspecialchars($_POST['']);
    $description = htmlspecialchars($_POST['']);
    $street = htmlspecialchars($_POST['']);
    $town = htmlspecialchars($_POST['']);
    $zip  = htmlspecialchars($_POST['']);
    $county = htmlspecialchars($_POST['']);

    //If any variable are empty send an error message. 
    if (empty($name) || empty($type) || empty($email) || empty($phone) || empty($description) || empty($street) || empty($town) || empty($zip) || empty($county)) {
      //Error message
      die("Please enter all the value");
    }
    
    //update the business the table
    $query = "UPDATE business SET name = ?, type = ?, email = ?, phone = ? , description = ?, street = ?, town = ?, zip = ?, county = ? where id = ?";
    $stmt = mysqli_stmt_init($conn);

    //if the query failed
    if (!mysqli_stmt_prepare($stmt, $query)) {
      die("Fatal error the business query failed");
    } else {
      //bind the variable to prepare the statement
      mysqli_stmt_bind_param($stmt, "sssssssssi", $name, $type, $email, $phone, $description, $street, $town, $zip, $county, $id);

      //execute the statement
      mysqli_stmt_execute($stmt);

      //free the memory
      mysqli_stmt_free_result($stmt);

      //close the statement
      mysqli_stmt_close($stmt);

      //send 200 message
      var_dump(http_response_code(200));
    }

    //close the connection
    mysqli_close($conn);
  } else {
    die("Select a business");
  }
} else {
  die("Please login");
}
