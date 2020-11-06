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

$owner_email =  htmlspecialchars($_POST['owner_email']);
$business_name = htmlspecialchars($_POST['name']);
$business_type = htmlspecialchars($_POST['type']);
$business_email = htmlspecialchars($_POST['email']);
$business_phone = htmlspecialchars($_POST['phone']);
$url = htmlspecialchars($_POST['url']);

//Check if the email is in the database
$owner_query = "SELECT * FROM business_owner where email = '$owner_email'";
$owner_result = $conn->query($owner_query);
$owner_info = $owner_result->fetch_array(MYSQLI_ASSOC);

//use the if statement to check if the 
//email is in the database.
if($owner_info){
  //get the id 
  $owner_id = $owner_info['id'];
  $flag = true;
}
else{
  $flag = false;
}

//if true store the data inside the
//database.
if($flag){
  //use the place holder to add the data into the user table
  //Placeholder method to store the data into the table
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
  var_dump(http_response_code(200));
}
else{
  //echo json_encode(["sent" => false, "message" => "Put the message here"]);
  var_dump(http_response_code(500));
}

//close the connection 
$conn->close();

?>