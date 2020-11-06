<?php
/*This file will add the address of the 
**the business. It will look for the business
**id from the business table and put it inside
**the address table as a foreign key.
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

$business_email = htmlspecialchars($_POST['email']);
$street = htmlspecialchars($_POST['street']);
$town = htmlspecialchars($_POST['town']);
$zip  = htmlspecialchars($_POST['zip']);
$city = htmlspecialchars($_POST['city']);


//Check if the email is in the database
$business_query = "SELECT * FROM business where email = '$business_email'";
$business_result = $conn->query($business_query);
$business_info = $business_result->fetch_array(MYSQLI_ASSOC);

//use the if statement to check if the 
//email is in the database
if($business_info){
  //get the id 
  $business_id = $business_info['id'];
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
  $stmt = $conn->prepare('INSERT INTO business_address VALUES(?,?,?,?,?,?)');
  
  $stmt->bind_param('iissss', $address_id, $b_id, $b_street, $b_town, $b_zip, $b_city);

  $address_id = null;
  $b_id = $business_id;
  $b_street = $street;
  $b_town = $town;
  $b_zip = $zip;
  $b_city = $city;
      
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