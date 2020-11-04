<?php

//include the file to connect with mysql 
require_once 'mysqlConn.php';

//connect to the database
$conn = new mysqli($hn,$un,$pw,$db);

//check for connect error with the DB
//and send the error
if ($conn->connect_error) {
  var_dump(http_response_code(500));
}

$first_name = htmlspecialchars($_POST['first_name']);
$last_name = htmlspecialchars($_POST['last_name']);
$email = htmlspecialchars($_POST['email']);


//Select all the field from the table and
//run the query.
$patron_query   = "SELECT * FROM patron";
$patron_result  = $conn->query($patron_query);
$rows = $patron_result->num_rows; 

//send an error for query not working
if (!$patron_result){
  var_dump(http_response_code(500));
}

//use the if statement to validate and 
//set the flag varaiable
if(!$flag_email){
  //use the place holder to add the data into the user table
  //Placeholder metahod to store the data into the table
  $stmt = $conn->prepare('INSERT INTO patron VALUES(?,?,?,?)');
  
  $stmt->bind_param('isss', $id, $fname, $lname, $p_email);

  $id = null;
  $fname = $first_name;
  $lname = $last_name;
  $p_email = $email;

  $stmt->execute(); //execute the insert statement
  $stmt->close(); //close the statement
  
  //echo json_encode(["sent" => true, "message" => "Put the message here"]);
  //var_dump(http_response_code(200));
}

//close the connection 
$conn->close();

?>