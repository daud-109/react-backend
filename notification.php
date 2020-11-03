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

//Select all the field from the table and
//run the query.
$patron_query   = "SELECT * FROM patron where email like '%gcrosston1@fema.gov%'";
$patron_result  = $conn->query($patron_query);
$patron_row = $patron_result->fetch_array(MYSQLI_ASSOC);

//send an error for query not working
if (!$patron_result){
  // var_dump(http_response_code(500));
  echo "Patron query did not work. \n";
}


//This will be use in the spreadsheet
$patron_id = $patron_row['patron_id'];

$spreadsheet_query   = "SELECT * FROM spreadsheet where patron_id = $patron_id";
$spreadsheet_result  = $conn->query($spreadsheet_query);
$spreadsheet_row = $spreadsheet_result->num_rows;


if (!$spreadsheet_result){
  // var_dump(http_response_code(500));
  echo "Spreadsheet query did not work. \n";
}

print_r($spreadsheet_row);
echo "\n";
for ($j = 0 ; $j < $spreadsheet_row ; ++$j) { 
  //Fetch a result row as an associative array
  $row = $spreadsheet_result->fetch_array(MYSQLI_ASSOC);

}

print_r($row);
echo "\n";
//close the connection 
$conn->close();
?>