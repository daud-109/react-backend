<?php
//include the file to connect with mysql 
    require_once 'mysqlConn.php';
    require_once 'function.php';

    //set the owner id to variable
    $owner_id = 9;
    

    //declare variable here
    $name = "business";
    $type = "business";
    $email = "business";
    $phone = "business";
    $description = "business";
    $street = "business";
    $town = "business";
    $zip  = "business";
    $county = "business";

    //Post variable here and fill in the post variable name

    //If any variable are empty send an error message. 
    if (empty($name) || empty($type) || empty($email) || empty($phone) || empty($description) || empty($street) || empty($town) || empty($zip) || empty($county)) {
      //Error message
      die("Please enter all the value");
    }

    //Insert value into the business table
    $query = "INSERT INTO business(owner_id, name, type, email, phone, description, street, town, zip, county) VALUES(?,?,?,?,?,?,?,?,?,?)";
    $stmt = mysqli_stmt_init($conn); //prepare statement

    //check if there is error in the previous query
    if (!mysqli_stmt_prepare($stmt, $query)) {
      die("Fatal error for the business query");
    } else {

      //Provide the the statement to bind, provide the type of variable and the variable itself.
      mysqli_stmt_bind_param($stmt, "isssssssss", $owner_id, $name, $type, $email, $phone, $description, $street, $town, $zip, $county);

      //execute the data provide by the user and the sql stamens.
      mysqli_stmt_execute($stmt);
    }

    //free the memory
    mysqli_stmt_free_result($stmt);

    //close the statement
    mysqli_stmt_close($stmt);

    //maybe display a message
   
    //var_dump(http_response_code(200));

    //close the connection 
    mysqli_close($conn);
  