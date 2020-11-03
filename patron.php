<?php

//include the file to connect with mysql 
require_once 'mysqlConn.php';

//connect to the database
$conn = new mysqli($hn,$un,$pw,$db);

//check for connect error with the DB
//and send the error
if ($conn->connect_error) {
  var_dump(http_response_code(500));
}

$patron_first_name = htmlspecialchars($_POST['patron_first_name']);
$patron_last_name = htmlspecialchars($_POST['patron_last_name']);
$patron_email = htmlspecialchars($_POST['patron_email']);

//Select all the field from the table and
//run the query.
$query   = "SELECT * FROM patron";
$result  = $conn->query($query);
$rows = $result->num_rows; 

//send an error for query not working
if (!$result){
  var_dump(http_response_code(500));
}

/*need to do somthing here*/

//use the if statement to validate and 
//set the flag varaiable
if(true){
  //use the place holder to add the data into the user table
  //Placeholder metahod to store the data into the table
  $stmt = $conn->prepare('INSERT INTO patron VALUES(?,?,?,?)');
  
  $stmt->bind_param('isss', $patron_id, $patron_fname, $patron_lname, $p_email);

  $patron_id = null;
  $patron_fname = $patron_first_name;
  $patron_lname = $patron_last_name;
  $p_email = $patron_email;

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