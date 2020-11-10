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
  exit();
}

$owner_email = $_POST['ownerEmail'];
$business_name = htmlspecialchars($_POST['name']);
$business_type = htmlspecialchars($_POST['businessType']);
$business_email = htmlspecialchars($_POST['email']);
$business_phone = htmlspecialchars($_POST['phone']);
$url = htmlspecialchars($_POST['url']);
$street = htmlspecialchars($_POST['street']);
$town = htmlspecialchars($_POST['town']);
$zip  = htmlspecialchars($_POST['zip']);
$county = htmlspecialchars($_POST['county']);

echo $owner_email . "\n" . $business_name . "\n" . $business_type . "\n" . $business_email. "\n" . $business_phone . "\n" . $url . "\n" . $street . "\n" . $town . "\n" . $zip . "\n" . $county;

//Check if the email is in the database
$owner_query = "SELECT * FROM business_owner where email = '$owner_email'";
$owner_result = $conn->query($owner_query);
$owner_info = $owner_result->fetch_array(MYSQLI_ASSOC);
//print_r($owner_info);

//use the if statement to check if the 
//email is in the database.
if($owner_info){
  //get the id 
  $owner_id = $owner_info['id'];
  $flag = true;
}
else{
  $flag = false;
  echo "Id is not set\n";
  exit();
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
  $test = $stmt->execute(); //execute the insert statement
  $stmt->close(); //close the statement

  $business_query = "SELECT * FROM business where email = '$business_email'";
  $business_result = $conn->query($business_query);
  $business_info = $business_result->fetch_array(MYSQLI_ASSOC);

  $new_id = $business_info['id'];

  $stmt = $conn->prepare('INSERT INTO business_address VALUES(?,?,?,?,?,?)');
  
  $stmt->bind_param('iissss', $address_id, $b_id, $b_street, $b_town, $b_zip, $b_county);

  $address_id = null;
  $b_id = $new_id;
  $b_street = $street;
  $b_town = $town;
  $b_zip = $zip;
  $b_county = $county;
  
  $stmt->execute(); //execute the insert statement
  $stmt->close(); //close the statement

  echo json_encode(["sent" => true, "message" => "Successfully Executed"]);
  //var_dump(http_response_code(200));
}
else{
  //echo json_encode(["sent" => false, "message" => "Put the message here"]);
 //var_dump(http_response_code(500));
}

//close the connection 
$conn->close();
?>