<?php
/*This file will verify user login information. 
**It will check if the Email and password 
**enter by user matches with the stored email
**and passwrod in the database. If it does not send an error.
*/

// header("Access-Control-Allow-Origin: *");
// $rest_json = file_get_contents("php://input");
//$_POST = json_decode($rest_json, true);


//include the file to connect with mysql 
require_once 'mysqlConn.php';

//connect to the database
$conn = new mysqli($hn,$un,$pw,$db);

//check for connect error with the DB
//and send the error
if ($conn->connect_error) {
  var_dump(http_response_code(500));
}


//These variable will hold user data
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

//Select all the field from the table and
//run the query.
$query   = "SELECT * FROM business_owner";
$result  = $conn->query($query);
$rows = $result->num_rows; 

//send an error for query not working
if (!$result){
  var_dump(http_response_code(500));
}

//Set flag for test
$flag_email = false;
$flag_password = false;

//The for loop will check if the email 
//and password are in the table.
for ($j = 0 ; $j < $rows ; ++$j) { 
  //Fetch a result row as an associative array
  $row = $result->fetch_array(MYSQLI_ASSOC); 
  //Check if the email exits in the database.
  if ($email == htmlspecialchars($row['email'])){
    $flag_email = true;
  }
  //check if the password exits in the database. 
  if (password_verify($password, htmlspecialchars($row['password']))){
    $flag_password = true;
  }
}

//Use the flag to give response back.
if($flag_email){
  if($flag_password){
    //all the information is correct
    //echo json_encode(["sent" => true, "message" => "Correct Information"]);
    var_dump(http_response_code(200));
  }
  else{
    //Enter the right password
    //echo json_encode(["sent" => false, "message" => "Password is not correct"]);
    var_dump(http_response_code(500));
  }
}
else{
  //Email is not correct
  //echo json_encode(["sent" => false, "message" => "Email is not correct"]);
  var_dump(http_response_code(500));
}
//close the connection 
$conn->close();


?>