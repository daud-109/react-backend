<?php
//include the file to connect with mysql 
require_once 'mysqlConn.php';
require_once 'function.php';

//set the owner id
$id = 1;

//declare variable here
$first_name = "Leora";
$last_name = "Gligorijevic";
$email = "rpoulsum2@reference.com"; //lgligorijevic0@intel.com
$password = "abc123";


//If any variable are empty send an error message. 
if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
  //Error message
  die("Please enter all the value");
}

//check if the email is taken


//Update the owner info
$query = "UPDATE business_owner SET first_name = ?, last_name = ?, email = ?, hash_password = ?
 where id = ?";
$stmt = mysqli_stmt_init($conn);

//if the query failed
if (!mysqli_stmt_prepare($stmt, $query)) {
  die("Fatal error the owner query failed");
} else {

  //side note: Have to come back here to check if the user enter the right password
  //hash the new password 
  $hash_password = password_hash($password, PASSWORD_DEFAULT);

  //bind the variable to prepare the statement
  mysqli_stmt_bind_param($stmt, "ssssi", $first_name, $last_name, $email, $hash_password, $id);

  //execute the statement
  mysqli_stmt_execute($stmt);

  //know check if the statement was affected
  mysqli_stmt_store_result($stmt);
  $row = mysqli_stmt_num_rows($stmt);

  if ($row === 1) {
    //send a 200 message
    var_dump(http_response_code(200));
    echo "Good\n";
  } else {
    echo "No Good\n";
  }

  //free the memory
  mysqli_stmt_free_result($stmt);

  //close the statement
  mysqli_stmt_close($stmt);
}

//close the connection
mysqli_close($conn);
