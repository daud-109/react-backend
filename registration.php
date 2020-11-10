<?php
/*This file will register the user. 
**The user have to enter the first name, last
**name, email, and password. Check if the 
**email is unique and register the user. if not
**send an error message. Talk to your team about
**the business registration.
*/

//include the file to connect with mysql 
require_once 'mysqlConn.php';

//connect to the database
$conn = new mysqli($hn,$un,$pw,$db);

//check for connect error with the DB
//and send the error
if ($conn->connect_error) {
  //var_dump(http_response_code(500));
  echo "Cannot connect to the database\n";
}

//User enter the data
$first_name = htmlspecialchars($_POST['firstName']);
$last_name = htmlspecialchars($_POST['lastName']);
$owner_email = htmlspecialchars($_POST['ownerEmail']);
$password = htmlspecialchars($_POST['password']);
$business_name = htmlspecialchars($_POST['businessName']);
$business_type = htmlspecialchars($_POST['businessType']);
$business_email = htmlspecialchars($_POST['businessEmail']);
$business_phone = htmlspecialchars($_POST['phone']);
$url = htmlspecialchars($_POST['url']);
$street = htmlspecialchars($_POST['street']);
$town = htmlspecialchars($_POST['town']);
$zip  = htmlspecialchars($_POST['zip']);
$county = htmlspecialchars($_POST['county']);

//Select all the field from the table and
//run the query.
$query   = "SELECT * FROM business_owner WHERE email = '$owner_email'";
$result  = $conn->query($query);
$row = $result->fetch_array(MYSQLI_ASSOC); 


//send an error for query not working
if (!$row){

  //use the place holder to add the data into the Owner table
  //Placeholder method to store the data into the table
  $stmt = $conn->prepare('INSERT INTO business_owner VALUES(?,?,?,?,?)');
  
  $stmt->bind_param('issss', $owner_id, $fName, $lName, $email_temp, $hash);

  $owner_id = null;
  $fName = $first_name;
  $lName = $last_name;
  $email_temp = $owner_email;
	$hash = password_hash($password, PASSWORD_DEFAULT);
   
  $stmt->execute(); //execute the insert statement
  $stmt->close(); //close the statement

  //Run this query to so we can get the owner id to put inside the business.
  $owner_query = "SELECT * FROM business_owner where email = '$owner_email'";
  $owner_result = $conn->query($owner_query);
  $owner_info = $owner_result->fetch_array(MYSQLI_ASSOC);

  $owner_id = $owner_info['id'];

  //Insert into the business table.
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

  //Run this query so now we can get business ID to put inside the address.
  $business_query = "SELECT * FROM business where email = '$business_email'";
  $business_result = $conn->query($business_query);
  $business_info = $business_result->fetch_array(MYSQLI_ASSOC);

  $new_id = $business_info['id'];

  //Insert data into the business address table.
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

  echo json_encode(["sent" => true, "message" => "Successful register"]);
  //var_dump(http_response_code(200));
  
}
else{
  echo json_encode(["sent" => false, "message" => "Email is taken"]);
  //var_dump(http_response_code(500));
}

//close the connection 
$conn->close();

?>