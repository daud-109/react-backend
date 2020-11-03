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

$business_street = htmlspecialchars($_POST['business_street']);
$business_town = htmlspecialchars($_POST['business_town']);
$business_zip  = htmlspecialchars($_POST['business_zip']);
$business_city = htmlspecialchars($_POST['business_city']);


//Select all the field from the table and
//run the query.
$query   = "SELECT * FROM business_address";
$result  = $conn->query($query);
$rows = $result->num_rows; 

//send an error for query not working
if (!$result){
  var_dump(http_response_code(500));
}

/*Here you have to go look for the business id
**to enter inside the address table
*/

//use the if statement to validate and 
//set the flag varaiable
if(true){
  //use the place holder to add the data into the user table
  //Placeholder metahod to store the data into the table
  $stmt = $conn->prepare('INSERT INTO business_address VALUES(?,?,?,?,?,?)');
  
  $stmt->bind_param('iissss', $address_id, $b_id, $b_street, $b_town, $b_zip, $b_city);

  $address_id = null;
  $b_id = $business_id;
  $b_street = $business_street;
  $b_town = $business_town;
  $b_zip = $business_zip;
  $b_city = $business_city;
      
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