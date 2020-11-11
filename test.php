<?php
/*This file will verify user login information. 
**It will check if the Email and password 
**enter by user matches with the stored email
**and password in the database. If it does not send an error.
*/
header('Content-Type: application/json');

//include the file to connect with mysql 
require_once 'mysqlConn.php';

//These variable will hold user data
$email = htmlentities(strip_tags("<h1>Hi My name's cool</h1>"));
//$password = htmlspecialchars();
echo $email . "\n";
//echo strip_tags($email);
//Select all the field from the table and
//run the query.
// $business_query = "SELECT * FROM business_owner where email = '$email'"; //this the query to run
// $business_result = $conn->query($business_query); //run the query
// $business_rows = $business_result->fetch_array(MYSQLI_ASSOC); //This is will get the data in associated array
//echo $business_rows['email'];

//close the connection 
$conn->close();

?>