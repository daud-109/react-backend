<?php
/*This file will register the business. It
**will look for the owner id from the owner 
**table and put inside the business table as
**foreign key. If the id is not found send error.
**Talk to your team about how this file will work
**or how they want it to work.
*/
header('Content-Type: application/json');
//include the file to connect with mysql 
require_once 'mysqlConn.php';

//connect to the database
$conn = new mysqli($hn,$un,$pw,$db);

//check for connect error with the DB
//and send the error
if ($conn->connect_error) {
  var_dump(http_response_code(500));
}

// $business_email = htmlspecialchars($_POST['business_name']);
$business_email = "swaith0@mozilla.org";

//Check if the email is in the database
$business_query = "SELECT * FROM business where email = '$business_email'";
$business_result = $conn->query($business_query);
$business_info = $business_result->fetch_array(MYSQLI_ASSOC);

//send an error for query not working
if (!$business_info){
  //var_dump(http_response_code(500));
  echo "Email is not correct.\n";
  exit();
}

//Store id to use inside the spreadsheet table
$business_id = $business_info['id'];

//Select from spreadsheet with business id
$spreadsheet_query = "SELECT * FROM spreadsheet where business_id = '$business_id'";
$spreadsheet_result  = $conn->query($spreadsheet_query);
$spreadsheet_rows = $spreadsheet_result->num_rows;

//send error if the query did not run
if (!$spreadsheet_rows){
  //var_dump(http_response_code(500));
  echo "No ID inside the spreadsheet.\n";
  exit();
}


//print_r($spreadsheet_rows);
$array = array();
//use the loop to get the patron id, which will 
//help us get the patron email from the patron table.
for ($j = 0; $j < $spreadsheet_rows ; $j++) { 
  //Fetch a result row as an associative array 
  $spreadsheet_info = $spreadsheet_result->fetch_array(MYSQLI_ASSOC);

  $patron_id = $spreadsheet_info['patron_id'];

  //Select all the field from the patron table
  $patron_query   = "SELECT * FROM patron where id = '$patron_id'";
  $patron_result  = $conn->query($patron_query);
  $patron_info = $patron_result->fetch_array(MYSQLI_ASSOC);

  
  $array[$j] = ["First Name" => $patron_info['first_name'], "Last Name" => $patron_info['last_name'], "Email" => $patron_info['email'], "temperature" => $spreadsheet_info['patron_id'],"Sheet Date" => $spreadsheet_info['sheet_date']];
}

//print_r($array);
$json = json_encode($array, JSON_PRETTY_PRINT);
//echo $spreadsheet_info;

echo $json;
//close the connection 
$conn->close();

?>