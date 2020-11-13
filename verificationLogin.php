<?php
/*This file will verify user login information. 
**It will check if the Email and password 
**enter by user matches with the stored email
**and password in the database. If it does not send an error.
*/
//header('Content-Type: application/json');

//include the file to connect with mysql 
require_once 'mysqlConn.php';


//These variable will hold user data
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

//Select all the field from the table and
//run the query.
$owner_query = "SELECT * FROM business_owner where email = '$email'"; //this the query to run
$owner_result = $conn->query($owner_query); //run the query
$owner_rows = $owner_result->fetch_array(MYSQLI_ASSOC); //This is will get the data in associated array

//Check if the user enter the right info
if ($owner_rows){
  if (password_verify($password, htmlspecialchars($owner_rows['hash_password']))){
    var_dump(http_response_code(200));
    session_start();
    $_SESSION['owner_email'] = $owner_rows['email'];
    //header("Location:C:\\Users\\16312\\Documents\\website-test\\src\\components\\Business\\BusinessInfo");
  }
  else{
    echo json_encode(["sent" => false, "message" => "Password is not correct"]);
    //$_POST['password'] = "Password is not correct";
  }
}
else{
  echo json_encode(["sent" => false, "message" => "Email is not correct"]);
  //$_POST['email'] = "Email is not correct";
}

//close the connection 
$conn->close();

?>