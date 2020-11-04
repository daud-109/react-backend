<?php
/*This file will register the business. It
**will look for the owner id from the owner 
**table and put inside the business table as
**foreign key. If the id is not found send error.
**Talk to your team about how this file will work
**or how they want it to work.
*/

//include the file to connect with mysql 
require_once 'mysqlConn.php';

//connect to the database
$conn = new mysqli($hn,$un,$pw,$db);

//check for connect error with the DB
//and send the error
if ($conn->connect_error) {
  var_dump(http_response_code(500));
}

// $business_name = "Jhon Pizza";
// $business_type = "Restraunt";
// $business_email = "JhonPizza@cool.com";
// $business_phone = "23-345-4435";
// $url = "JhonPizza.com";
$owner_email = "jwicks4@mashable.com";


$owner_query = "SELECT * FROM business_owner";
$owner_result = $conn->query($owner_query);
// $owner_rows = $owner_result->num_rows;
print_r($owner_result);
echo "\n";
$owner_info = $owner_result->fetch_array(MYSQLI_ASSOC);

//send an error for query not working
if ($owner_info){
  print_r($owner_info);
  $test_email = $owner_info['id'];
  echo $test_email;
}

//close the connection 
$conn->close();


?>