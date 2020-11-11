<?php

//include the file to connect with mysql 
require_once 'mysqlConn.php';


session_start();

if(isset($_SESSION['owner_email'])){

  $owner_email = $_SESSION['owner_email'];
  
  $owner_query   = "SELECT * FROM business_owner where email = '$owner_email'";
  $owner_result  = $conn->query($owner_query);
  $owner_info = $owner_result->fetch_array(MYSQLI_ASSOC);

  $owner_id = $owner_info['id'];

  $first_name = htmlspecialchars($_POST['firstName']);
  $last_name = htmlspecialchars($_POST['lastName']);
  $email = htmlspecialchars($_POST['email']);
  $temperature = htmlspecialchars($_POST['temp']);
  $date = htmlspecialchars($_POST['date']);

  //use the email to find the id of the business
  $business_query = "SELECT * FROM business where owner_id = '$owner_id'";
  $business_result = $conn->query($business_query);
  $business_info = $business_result->fetch_array(MYSQLI_ASSOC);
  
  $business_id = $business_info['id'];

  //Select all the field from the patron table and
  //get the id to store it in the table.
  $patron_query   = "SELECT * FROM patron where email = '$email'";
  $patron_result  = $conn->query($patron_query);
  $info = $patron_result->fetch_array(MYSQLI_ASSOC);


  //send an error for query not working
  if (!$info){
    $stmt = $conn->prepare('INSERT INTO patron VALUES(?,?,?,?)');

    $stmt->bind_param('isss', $id, $fname, $lname, $p_email);

    $id = null;
    $fname = $first_name;
    $lname = $last_name;
    $p_email = $email;

    $stmt->execute(); //execute the insert statement
    $stmt->close(); //close the statement

    //Now look for the email which is going to help us find the id.
    $patron_query   = "SELECT * FROM patron where email = '$email'";
    $patron_result  = $conn->query($patron_query);
    $info = $patron_result->fetch_array(MYSQLI_ASSOC);

    //set the id to store inside the 
    $patron_id = $info['id'];

    $stmt = $conn->prepare('INSERT INTO spreadsheet VALUES(?,?,?,?,?)');

    $stmt->bind_param('iiiss', $spreadsheet_id, $b_id, $p_id, $p_temperature, $p_date);

    $spreadsheet_id = NULL;
    $b_id = $business_id;
    $p_id = $patron_id;
    $p_temperature = $temperature;
    $p_date = $date;

    $stmt->execute(); //execute the insert statement
    $stmt->close(); //close the statement

  }
  else{
    //If the patron is already exits just use their
    //id to put inside the spreadsheet.

    //patron id
    $patron_id = $info['id'];

    //Place holder method to insert value. 
    $stmt = $conn->prepare('INSERT INTO spreadsheet VALUES(?,?,?,?,?)');

    $stmt->bind_param('iiiss', $spreadsheet_id, $b_id, $p_id, $p_temperature, $p_date);

    $spreadsheet_id = NULL;
    $b_id = $business_id;
    $p_id = $patron_id;
    $p_temperature = $temperature;
    $p_date = $date;

    $stmt->execute(); //execute the insert statement
    $stmt->close(); //close the statement
  }

  //close the connection 
  $conn->close();
}
else{
  die("You must login");
}
?>