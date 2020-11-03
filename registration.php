<?php
/*This file will register the user. 
**The user have to enter the first name, last
**name, email, and password. Check if the 
**email is unique and register the user. if not
**send an error message. Talk to your team about
**the business registration.
*/

//header("Access-Control-Allow-Origin: *");
//$rest_json = file_get_contents("php://input");
//$_POST = json_decode($rest_json, true);

// $request = json_decode($rest_json);
// print_r($request);

//include the file to connect with mysql 
require_once 'mysqlConn.php';

//connect to the database
$conn = new mysqli($hn,$un,$pw,$db);

//check for connect error with the DB
//and send the error
if ($conn->connect_error) {
  var_dump(http_response_code(500));
}

//User enter the data
$first_name = htmlspecialchars($_POST['firstName']);
$last_name = htmlspecialchars($_POST['lastName']);
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

//create a flag variable for boolean.
$flag_email = false;

//Use the for loop to check if the email is taken.
//Side not think about using the select with where
//clasue to look for the email.
for ($j = 0 ; $j < $rows ; ++$j) { 
  
  //Fetch a result row as an associative array
  $row = $result->fetch_array(MYSQLI_ASSOC); 

  //use the identical to check if the email is not same.
  if ($email == htmlspecialchars($row['email'])){
    //Email is taken
    $flag_email = true;
  }
}

//Check
if(!$flag_email){
  //use the place holder to add the data into the user table
  //Placeholder metahod to store the data into the table
  $stmt = $conn->prepare('INSERT INTO business_owner VALUES(?,?,?,?,?)');
  
  $stmt->bind_param('issss', $owner_id, $fName, $lName, $hash, $email_temp);

  $owner_id = null;
  $fName = $firstName;
  $lName = $lastName;
	$hash = password_hash($password, PASSWORD_DEFAULT);
  $email_temp = $email;
      
  $stmt->execute(); //execute the insert statement
  $stmt->close(); //close the statement
  echo "In the database\n";

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