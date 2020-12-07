<?php
/*This file will modify patron info that 
**patron wants to modify.
*/

//start session
session_start();

//check if teh user is logged in
if (isset($_SESSION['patron_id'])) {

  //include the file to connect with mysql 
  require_once '../mysqlConn.php';
  require_once '../function.php';

  //set the patron id
  $id = $_SESSION['patron_id'];

  //declare variable here
  $first_name = $last_name = $email = $password = "";

  //Post variable here and fill in the post variable name
  $first_name = htmlspecialchars($_POST['first_name']);
  $last_name = htmlspecialchars($_POST['last_name']);
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);

  //If any variable are empty send an error message. 
  if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
    //Error message
    die("Please enter all the value");
  }

  //check for the password confirmation
  
  //Update the owner info
  $query = "UPDATE patron SET first_name = ?, last_name = ?, email = ?, hash_password = ?
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

    //Check if the statement got executed
    if (mysqli_stmt_execute($stmt)){
      //send successful message
      echo "Successful";
    }else{
      //this might be if the email is taken
      echo "Fatal error with the execute statement";
    }

    //free the memory
    mysqli_stmt_free_result($stmt);

    //close the statement
    mysqli_stmt_close($stmt);
  }

  //close the connection
  mysqli_close($conn);
} else {
  die("Please login");
}
