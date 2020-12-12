<?php

require_once './mysqlConn.php';

$x = "name";
$name = "ri";
$name = "%$name%";
//Select all of the business
$query = "SELECT name, type, street, town, zip, county 
          FROM business 
          WHERE $x LIKE ?";
$stmt = mysqli_stmt_init($conn);

//if the business query is not setup properly
if (!mysqli_stmt_prepare($stmt, $query)) {
  //display error
  die("Fatal error the business select query failed");
} else {

  mysqli_stmt_bind_param($stmt, "s", $name);

  //execute the statement
  if (mysqli_stmt_execute($stmt)) {
    //get result
    $result = mysqli_stmt_get_result($stmt);

    //array to hold business info
    $business_info = array();

    //help with incrementing
    $i = 0;

    //get the the data in associated array manner
    while($row = mysqli_fetch_assoc($result)){
      //store the data inside the array
      $business_info[$i] = ["id" => $row['id'],"name" => $row['name'], "type" => $row['type'], "street" => $row['street'], "town" => $row['town'], "zip" => $row['zip'], "county" => $row['county']];

      //increment
      $i++;
    }

    //encode the array into json formate
    $json = json_encode($business_info, JSON_PRETTY_PRINT);

    //display the business info
    echo $json;
    
  } else {
    echo "Fatal error with execution";
  }
}