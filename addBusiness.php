<?php
/*This file will allow the owner to add
**a new business inside the database. 
*/

//Make sure the user got to this page by hitting the 
//submitting the button and not by typing the description.
if ($_SERVER['REQUEST_METHOD'] === "POST") {

  //Start session
  session_start();

  if (isset($_SESSION['owner_id'])) {

    //include the file to connect with mysql 
    require_once 'mysqlConn.php';
    require_once 'function.php';

    //set the owner id to variable
    $owner_id = $_SESSION['owner_id'];

    //declare variable here
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

    //Insert value into the business table
    $query = "INSERT INTO business(owner_id, name, type, email, phone, description, street, town, zip, county) VALUES(?,?,?,?,?,?,?,?,?,?)";
    $stmt = mysqli_stmt_init($conn); //prepare statement

    //check if there is error in the previous query
    if (!mysqli_stmt_prepare($stmt, $query)) {
      die("Fatal error for the business query");
    } else {

      //Provide the the statement to bind, provide the type of variable and the variable itself.
      mysqli_stmt_bind_param($stmt, "isssssssss", $owner_id, $name, $type, $email, $phone, $description, $street, $town, $zip, $county);

      //execute the data provide by the user and the sql stamens.
      mysqli_stmt_execute($stmt);
    }

    //free the memory
    mysqli_stmt_free_result($stmt);

    //close the statement
    mysqli_stmt_close($stmt);

    //maybe display a message
    var_dump(http_response_code(200));

    //close the connection 
    mysqli_close($conn);
  } else {
    die("Please login");
  }
} else {

  //send error because user try to get inside the file without clicking on the submit button
  die(http_response_code(404));
}