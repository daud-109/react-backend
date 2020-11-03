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

$temperatur = htmlspecialchars($_POST['temperatur']);
$date = htmlspecialchars($_POST['date']);

//Select all the field from the table and
//run the query.
$query   = "SELECT * FROM business_spreadsheet";
$result  = $conn->query($query);
$rows = $result->num_rows; 

//send an error for query not working
if (!$result){
  var_dump(http_response_code(500));
}

/*Got to the business and patron table and  
**get the id to store in the spreadsheet tabel
*/

//use the if statement to validate and 
//set the flag varaiable
if(true){
  //use the place holder to add the data into the user table
  //Placeholder metahod to store the data into the table
  $stmt = $conn->prepare('INSERT INTO business_spreadsheet VALUES(?,?,?,?,?)');
  
  $stmt->bind_param('iiiss', $spreadsheet_id, $b_id, $p_id, $p_temperatur, $p_date);

  $spreadsheet_id = NULL;
  $b_id = $business_id;
  $p_id = $patron_id;
  $p_temperatur = $temperatur;
  $p_date = $date;

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