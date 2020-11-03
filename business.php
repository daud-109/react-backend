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

$business_name = htmlspecialchars($_POST['businessName']);
$business_type = htmlspecialchars($_POST['businessType']);
$business_email = htmlspecialchars($_POST['businessEmail']);
$business_phone = htmlspecialchars($_POST['businessPhone']);
$url = htmlspecialchars($_POST['url']);

//Select all the field from the table and
//run the query.
$query   = "SELECT * FROM business";
$result  = $conn->query($query);
$rows = $result->num_rows; 

//send an error for query not working
if (!$result){
  var_dump(http_response_code(500));
}

/*Here you have to go look for the business owner id
**to enter inside the business table
*/

//use the if statement to validate and 
//set the flag varaiable
if(true){
  //use the place holder to add the data into the user table
  //Placeholder metahod to store the data into the table
  $stmt = $conn->prepare('INSERT INTO business VALUES(?,?,?,?,?,?,?)');
  
  $stmt->bind_param('iisssss', $business_id, $b_owner_id, $b_name, $b_type, $b_email, $b_phone_Number, $b_url);

  $business_id = null;
  $b_owner_id = $owner_id;
  $b_name = $business_name;
  $b_type = $business_type;
  $b_email= $business_email;
  $b_phone_Number = $business_phone;
  $b_url = $url;
      
  $stmt->execute(); //execute the insert statement
  $stmt->close(); //close the statement
 //echo json_encode(["sent" => true, "message" => "Put the message here"]);
  //var_dump(http_response_code(200));
}
else{
  //echo json_encode(["sent" => false, "message" => "Put the message here"]);
  //var_dump(http_response_code(500));
}

//close the connection 
$conn->close();


?>