<?php

//include the file to connect with mysql 
require_once 'mysqlConn.php';

//connect to the database
$conn = new mysqli($hn,$un,$pw,$db);

//check for connect error with the DB
//and send the error
if ($conn->connect_error) {
  var_dump(http_response_code(500));
  die();
}

//Post variables 
$date = htmlspecialchars($_POST['date']);
$business_email= htmlspecialchars($_POST['email']);

//Select from business with given email so 
//we can get the id with the help of associated array.
$business_query   = "SELECT * FROM business where email = '$business_email'";
$business_result  = $conn->query($business_query);
$business_info = $business_result->fetch_array(MYSQLI_ASSOC);

//send an error for query not working
if (!$business_info){
  //var_dump(http_response_code(500));
  echo "Email is not correct.\n";
  die();
}

//Store id to use inside the spreadsheet table
$business_id = $business_info['id'];

//Select from spreadsheet with business id and date to
//see which patron where presented on that date.
$spreadsheet_query = "SELECT * FROM spreadsheet where business_id = '$business_id' and sheet_date = '$date'";
$spreadsheet_result  = $conn->query($spreadsheet_query);
$spreadsheet_rows = $spreadsheet_result->num_rows;

//send error if the query did not run
if (!$spreadsheet_rows){
  //var_dump(http_response_code(500));
  echo "No info on this date.\n";
  die();
}

//create an array
$testID = array();

//use the loop to get the patron id, which will 
//help us get the patron email from the patron table.
for ($j = 0; $j < $spreadsheet_rows ; $j++) { 
  //Fetch a result row as an associative array 
  $row = $spreadsheet_result->fetch_array(MYSQLI_ASSOC);

  //Get the patron id and store it in the array
  $testID[$j] = $row['patron_id'];
}

//get the unique id and put inside the array
$x = array_unique($testID);

//sort the array which will also have 
//all the index which will prevent error
//for loop.
sort($x);

//Use the for loop to get the email of the 
//patron. 
for ($i = 0; $i < sizeof($x); $i++){
  //Look for the patron email to send the notification.
  $patron_query   = "SELECT * FROM patron where id = '$x[$i]'";
  $patron_result  = $conn->query($patron_query);
  $patron_info = $patron_result->fetch_array(MYSQLI_ASSOC);

  //Display the email
  echo $x[$i] . " " . $patron_info['email'] . "\n";
}

//close the connection 
$conn->close();
?>