<?php
/*This file will register the the owner, business
**and the address.Check if the email is unique and 
**register the user. if not send an error message. 
**Make sure to get the id to store inside the related
**table.We are going to use prepare statement
**because it will help prevent sql injection.
*/


//Make sure the user got to this page by hitting the submitting
//the button and not by typing the url.
if ($_SERVER['REQUEST_METHOD'] === "POST") {

  //Assign everything empty string
  $first_name = $last_name = $owner_email = $password = $business_name = $business_type = $business_email = $business_phone = $url = $street = $town = $zip  = $county = "";

  //include the file to connect with mysql 
  require_once 'mysqlConn.php';
  require_once 'function.php';

  //This will go in the owner table
  $first_name = htmlspecialchars($_POST['firstName']);
  $last_name = htmlspecialchars($_POST['lastName']);
  $owner_email = htmlspecialchars($_POST['ownerEmail']);
  $password = htmlspecialchars($_POST['password']);

  //This will go in the business table
  $business_name = htmlspecialchars($_POST['businessName']);
  $business_type = htmlspecialchars($_POST['businessType']);
  $business_email = htmlspecialchars($_POST['businessEmail']);
  $business_phone = htmlspecialchars($_POST['phone']);
  $url = htmlspecialchars($_POST['url']);

  //This will go in the address table
  $street = htmlspecialchars($_POST['street']);
  $town = htmlspecialchars($_POST['town']);
  $zip  = htmlspecialchars($_POST['zip']);
  $county = htmlspecialchars($_POST['county']);

  //If any variable are empty send an error message. 
  if (empty($first_name) || empty($last_name) || empty($owner_email) || empty($password) || empty($business_name)   || empty($business_type) || empty($business_email) || empty($business_phone) || empty($url) || empty($street) || empty($town) || empty($zip) || empty($county)) {

    //send error message
    echo json_encode(["sent" => false, "message" => "Please enter all the value"]);
    die();
  }

  //Check if the email exits in the database.
  $query = "SELECT * FROM business_owner WHERE email =?";
  $stmt = mysqli_stmt_init($conn); //prepare statement

  //Check if the query run
  if (!mysqli_stmt_prepare($stmt, $query)) {

    //End the program if the query does not run
    die("Fatal Error for the owner query to check if the email is taken");
  } else {

    //Provide the the statement to bind, provide the type of variable and the variable itself.
    mysqli_stmt_bind_param($stmt, "s", $owner_email);

    //execute the data provide by the user and the sql stamens.
    mysqli_stmt_execute($stmt);

    //This take the value from the database and store it in the stmt variable.  
    mysqli_stmt_store_result($stmt); //This is fetching data from the database

    //Number of result or rows.
    $row = mysqli_stmt_num_rows($stmt);

    //check if the email exits by number of row.
    //So if one row is effect that mean email exits.
    if ($row > 0) {
      //Display error
      die(json_encode(["sent" => false, "message" => "Email is taken"]));
    } else {

      //use the place holder to add the data into the Owner table
      //Placeholder method to store the data into the table
      $query = "INSERT INTO business_owner (first_name, last_name, email, hash_password) VALUES(?,?,?,?)";
      $stmt = mysqli_stmt_init($conn); //prepare statement

      //if the query fails than run this statement
      if (!mysqli_stmt_prepare($stmt, $query)) {
        die("Fatal error for insert owner query");
      } else {
        //hast the password using the default which is the BCRYPT hash password function
        $hash = password_hash($password, PASSWORD_DEFAULT);

        //Provide the the statement to bind, provide the type of variable and the variable itself.
        mysqli_stmt_bind_param($stmt, "ssss", $first_name, $last_name, $owner_email, $hash);

        //execute the data provide by the user and the sql stamens.
        mysqli_stmt_execute($stmt);

        //get the id from this last executed inset query 
        $owner_id = mysqli_insert_id($conn);

        //Insert value into the business table
        $query = "INSERT INTO business(owner_id, name, type, email, phone, url) VALUES(?,?,?,?,?,?)";
        $stmt = mysqli_stmt_init($conn); //prepare statement

        //check if there is error in the previous query
        if (!mysqli_stmt_prepare($stmt, $query)) {

          die("Fatal error fro the business query");
        } else {

          //Provide the the statement to bind, provide the type of variable and the variable itself.
          mysqli_stmt_bind_param($stmt, "isssss", $owner_id, $business_name, $business_type, $business_email, $business_phone, $url);

          //execute the data provide by the user and the sql stamens.
          mysqli_stmt_execute($stmt);

          //get the id from this last executed inset query 
          $business_id = mysqli_insert_id($conn);

          //insert into the address table
          $query = "INSERT INTO business_address(business_id, street, town, zip, county) VALUES(?,?,?,?,?)";
          $stmt = mysqli_stmt_init($conn); //prepare statement

          //check if there is error in the previous query
          if (!mysqli_stmt_prepare($stmt, $query)) {

            die("Fatal error for the insert address query");
          } else {

            //Provide the the statement to bind, provide the type of variable and the variable itself.
            mysqli_stmt_bind_param($stmt, "issss", $business_id, $street, $town, $zip, $county);

            //execute the data provide by the user and the sql stamens.
            mysqli_stmt_execute($stmt);
          }
        }

        //free the memory
        mysqli_stmt_free_result($stmt);

        //close the statement
        mysqli_stmt_close($stmt);

        echo json_encode(["sent" => true, "message" => "Successful register"]);
        var_dump(http_response_code(200));
      }
    }
  }

  //close the connection 
  mysqli_close($conn);
} else {

  //send error because user try to get inside the file without clicking on the submit button
  die(http_response_code(404));
}
