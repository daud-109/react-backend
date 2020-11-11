<?php
/*This file has the variable for connecting
**with mysql database.
*/
$hn = 'localhost';
$db = 'covid19';
$un = 'root';
$pw = 'mysql';

//connect to the database
$conn = new mysqli($hn, $un, $pw, $db);

//check for connect error with the DB
//and send the error
if ($conn->connect_error) {
  //var_dump(http_response_code(500));
  die();
}

?>