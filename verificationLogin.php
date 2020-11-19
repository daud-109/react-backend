<?php
/*This file will verify user login information. 
**It will check if the Email and password 
**enter by user matches with the stored email
**and password in the database. If it does not send an error.
*/
// header('Content-Type: application/json');

//Make sure the user got to this page by hitting the submitting
//the button and not by typing the url.
if ($_SERVER['REQUEST_METHOD'] === "POST") {

  //include the file to connect with mysql 
  require_once 'mysqlConn.php';
  require_once 'function.php';

  //Assign everything empty string
  $email = $password = "";

  //These variable will hold user data
  $email = htmlspecialchars(validateAll($_POST['email']));
  $password = htmlspecialchars(validateAll($_POST['password']));

  //Check if the user enter all the data
  if (!(empty($email) && empty($password))) {

    //Select all the field from the table and
    //run the query.
    $owner_query = "SELECT * FROM business_owner where email = ?";
    $stmt  = mysqli_stmt_init($conn);

    //check if the query run 
    if (mysqli_stmt_prepare($stmt, $owner_query)) {

      //actually running the query
      mysqli_stmt_bind_param($stmt, "s", $email);
      mysqli_stmt_execute($stmt);

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
          
          //$_SESSION['owner_email'] = $row['email'];
          $_SESSION['owner_id'] = $row ['id']; //set the session value

          die(http_response_code(200));

        } else {

          echo json_encode(["sent" => false, "message" => "Email or Password is not correct"]);
          die(http_response_code(401));

        }

      } else {
        //if nothing is fetch from the data base that mean there is no email in the database. 
        echo json_encode(["sent" => false, "message" => "Email or Password is not correct"]);
        
        die(http_response_code(401));
      }

    } else {
      //if the query does not run display this message
      die("Fatal Error");
    }

  }else{
    //This is the second if statement
    die("Make sure to enter the email and password");

    //make sure to throw an error here
    
  }

  //free the result memory
  mysqli_stmt_free_result($stmt);

  //close the statement 
  mysqli_stmt_close($stmt);

  //close the connection 
  mysqli_close($conn);

} else {
  //send error because user try to get inside the file without clicking on the submit button
  die(http_response_code(404));
}
