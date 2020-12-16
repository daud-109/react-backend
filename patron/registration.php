<?php
/*This file will register the patron and check if 
**the owner email is unique and than register the user. 
**if the email is taken then check if the password is 
**empty and the first and last name matches with the database.
**Then register the user.
*/
header('Content-Type: application/json');

//Make sure the user got to this page by hitting the 
//submitting the button and not by typing the description.
if ($_SERVER['REQUEST_METHOD'] === "POST") {

  //Assign everything empty string
  $first_name = $last_name = $email = $password = "";

  //include the file to connect with mysql 
  require_once '../mysqlConn.php';
  require_once '../function.php';

  //This will go in the patron table
  $first_name = htmlspecialchars($_POST['firstName']);
  $last_name = htmlspecialchars($_POST['lastName']);
  $email = htmlspecialchars($_POST['email']); //check
  $password = htmlspecialchars($_POST['password']); //check

  //If any variable are empty send an error message. 
  if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
    //send error message
    die(http_response_code(409));
  }

  //Check if the email exits in the database.
  $query = "SELECT * FROM patron WHERE email =?";
  $stmt = mysqli_stmt_init($conn); //prepare statement

  //Check if the query run
  if (!mysqli_stmt_prepare($stmt, $query)) {
    //End the program if the query does not run
    die(http_response_code(409));
  } else {

    //Provide the the statement to bind, provide the type of variable and the variable itself.
    mysqli_stmt_bind_param($stmt, "s", $email);

    //execute the data provide by the user and the sql stamens.
    mysqli_stmt_execute($stmt);

    //get result
    $result = mysqli_stmt_get_result($stmt);

    //now use this to check if the email is taken but the password is empty
    if ($row = mysqli_fetch_assoc($result)) {

      //check if the password is in the database
      if ($row['hash_password']) {
        //if the password is filled
        echo "Email is taken";
        die(http_response_code(409));
      } else {

        //check if the first name match 
        if ($row['first_name'] !== $first_name) {

          //if the first name does not match 
          echo "Please enter the correct first name";
          die(http_response_code(409));

          //inside of this check if the last name match
          if ($row['last_name'] !== $last_name) {

            //add the last name key to array
            echo "Please enter the correct last name";
            die(http_response_code(409));
          } else {

            //if the last name is correct, just send the first name error
            echo "Not first name";
            die(http_response_code(409));
          }

          //if the first name is correct now check if the last name match
        } else if ($row['last_name'] !== $last_name) {

          //if last name does not match
          echo json_encode(["message" => "Please enter the correct last name"], JSON_PRETTY_PRINT);
        } else {

          //sign up the patron
          $query = "UPDATE patron SET hash_password = ? where id = ?";
          $stmt = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($stmt, $query)) {

            //send error if the query does not work
            die(http_response_code(409));
          } else {

            //store the id and the password
            $id = $row['id'];

            //hast the password
            $hash_password = password_hash($password, PASSWORD_DEFAULT);

            //bind the variable to prepare the statement
            mysqli_stmt_bind_param($stmt, "si", $hash_password, $id);

            //execute the statement
            if (!mysqli_stmt_execute($stmt)) {

              //if it does not execute
              die(http_response_code(409));
            }
          }
        }
      }
    } else {

      //use the place holder to add the data into the patron table
      //Placeholder method to store the data into the table
      $query = "INSERT INTO patron (first_name, last_name, email, hash_password) VALUES(?,?,?,?)";
      $stmt = mysqli_stmt_init($conn); //prepare statement

      //if the query fails than run this statement
      if (!mysqli_stmt_prepare($stmt, $query)) {
        die("Fatal error for insert patron query");
      } else {
        //hast the password using the default which is the BCRYPT hash password function
        $hash = password_hash($password, PASSWORD_DEFAULT);

        //Provide the the statement to bind, provide the type of variable and the variable itself.
        mysqli_stmt_bind_param($stmt, "ssss", $first_name, $last_name, $email, $hash);

        //execute the data provide by the user and the sql stamens.
        if (!mysqli_stmt_execute($stmt)) {
          //if it does not execute 
          die(http_response_code(409));
        }

        //free the memory
        mysqli_stmt_free_result($stmt);

        //close the statement
        mysqli_stmt_close($stmt);
      }
    }
  }

  //close the connection 
  mysqli_close($conn);
} else {

  //send error because user try to get inside the file without clicking on the submit button
  die(http_response_code(404));
}
