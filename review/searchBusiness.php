<?php
/*Display all of the business so the 
**user can select a business to check
**for the review of the business
*/
die();
header('Content-Type: application/json');


//include the file to connect with mysql 
require_once '../mysqlConn.php';

//declare variable here
$search_by = $search_for = '';


//Post variable here and fill in the post variable name
$search_by = htmlspecialchars($_POST['search_by']);
$search_for = htmlspecialchars($_POST['search_for']);

//if data is empty
if(empty($search_by) || empty($search_for)){
  die("Please enter all of the value");
}

//Select all of the business
$query = "SELECT id, name, type, street, town, zip, county 
          FROM business 
          WHERE $search_by LIKE ?";
$stmt = mysqli_stmt_init($conn);

//if the business query is not setup properly
if (!mysqli_stmt_prepare($stmt, $query)) {
  //display error
  die("Fatal error the business select query failed");
} else {

  $search_for = "%$search_for%";

  //bind the statement
  mysqli_stmt_bind_param($stmt, "s", $search_for);

  echo $search_by . " " . $search_for;
  //execute the statement
  if (mysqli_stmt_execute($stmt)) {

    //get result
    $result = mysqli_stmt_get_result($stmt);

    //array to hold business info
    $business_info = array();

    //help with incrementing
    $i = 0;

    //get the the data in associated array manner
    while ($row = mysqli_fetch_assoc($result)) {
      //store the data inside the array
      $business_info[$i] = ["id" => $row['id'], "name" => $row['name'], "type" => $row['type'], "street" => $row['street'], "town" => $row['town'], "zip" => $row['zip'], "county" => $row['county']];

      //increment
      $i++;
    }

    //encode the array into json formate
    $json = json_encode($business_info, JSON_PRETTY_PRINT);

    //display the business info
    echo $json;
  } else {
    echo "Fatal error with execution";
    die(http_response_code(401));
  }
}


//free the memory
mysqli_stmt_free_result($stmt);

//close the statement
mysqli_stmt_close($stmt);

//close the connection 
mysqli_close($conn);
