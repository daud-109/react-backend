<?php
/*This file will verify user login information. 
**It will check if the Email and password 
**enter by user matches with the stored email
**and password in the database. If it does not send an error.
*/

//include the file to connect with mysql 
require_once 'mysqlConn.php';

//connect to the database
$conn = new mysqli($hn,$un,$pw,$db);

//check for connect error with the DB
//and send the error
if ($conn->connect_error) {
  var_dump(http_response_code(500));
  die();
}


//These variable will hold user data
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

//Select all the field from the table and
//run the query.
$business_query = "SELECT * FROM business_owner where email = '$email'"; //this the query to run
$business_result = $conn->query($business_query); //run the query
$business_rows = $business_result->fetch_array(MYSQLI_ASSOC); //This is will get the data in associated array

//Check if the user enter the right info
if ($business_rows){
  if (password_verify($password, htmlspecialchars($business_rows['hash_password']))){
    var_dump(http_response_code(200));
  }
  else{
    echo json_encode(["sent" => false, "message" => "Password is not correct"]);
  }
}
else{
  echo json_encode(["sent" => false, "message" => "Email is not correct"]);
}

//close the connection 
$conn->close();

?>