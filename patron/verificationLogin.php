<?php
/*This file will verify user login information. 
**It will check if the email and password 
**enter by user matches with the stored email
**and password in the database. If the email and 
**the password does not match then send an error. 
*/

//check if the 
if ($_SERVER['REQUEST_METHOD'] === "POST") {

  //include the file to connect with mysql 
  require_once '../mysqlConn.php';
  require_once '../function.php';

  //Assign everything empty string
  $email = $password = "";

  //These variable will hold user data
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);

  //Check if the user enter all the data
  if (isset($email) && isset($password)) {

    //Select all the field from the table and
    //run the query.
    $query = "SELECT * FROM patron where email = ?";
    $stmt  = mysqli_stmt_init($conn);

    //check if the query run 
    if (mysqli_stmt_prepare($stmt, $query)) {

      //actually running the query
      mysqli_stmt_bind_param($stmt, "s", $email);

      if (mysqli_stmt_execute($stmt)) {
        //get the result
        $result = mysqli_stmt_get_result($stmt);

        //check if there is some data with the given email
        if ($row = mysqli_fetch_assoc($result)) {
          
          //This variable will be set to boolean variable.
          $password_check = password_verify($password, $row['hash_password']); //Check if the password match

          //Check if the variable is correct than start the session.
          if ($password_check == TRUE) {

            //start session
            session_start();

            //set session variable
            $_SESSION['patron_id'] = $row['id']; //set the session value

          } else {
            //echo json_encode(["sent" => false, "message" => "Email or Password is not correct"]);
            die(http_response_code(401));
          }
        } else {

          //if nothing is fetch from the data base that mean there is no email in the database. 
          die(http_response_code(401));

        }
      } else {
        //Fatal error with execute statement
        die(http_response_code(409));
      }
    } else {
      //if the query does not run display this message
      die(http_response_code(409));
    }
  } else { //this else is the 
    //This is the second if statement make sure to enter the email and password
    die(http_response_code(409));
  }

  //free the result memory
  mysqli_stmt_free_result($stmt);

  //close the statement 
  mysqli_stmt_close($stmt);

  //close the connection 
  mysqli_close($conn);
} else { //this else belong to the first if statement.
  //send error because user try to get inside the file without clicking on the submit button
  die(http_response_code(404));
}
