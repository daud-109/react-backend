<?php
/*This file will select a business and start a session for that business.
**It good to test if the email exits in the database before starting the session.
**Talk to your team if they want send data as json or post (most likely post).
**if using post make sure the user cannot edit the info
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  //Start session
  session_start();

  if (isset($_SESSION['owner_id'])) {

    //include the file to connect with mysql 
    require_once 'mysqlConn.php';
    require_once 'function.php';

    //Unset the business session to 
    //start new session
    if (isset($_SESSION['business_id'])) {
      unset($_SESSION['business_id']);
    }

    //declare the variable
    $email = "";

    //Post variables 
    $email = htmlspecialchars($_POST['email']);

    //check if the post is empty
    if (empty($email)) {
      die("No email was enter");
    } else {

      //check if the email exits
      $query = "SELECT * FROM business where email = ?";
      $stmt = mysqli_stmt_init($conn);

      //if the query does not run
      if (!mysqli_stmt_prepare($stmt, $query)) {
        die("Fatal error with query");
      } else {
        //prepare the statement by binding
        mysqli_stmt_bind_param($stmt, "s", $email);

        //execute the statement
        mysqli_stmt_execute($stmt);

        //get the result to check the data
        $result = mysqli_stmt_get_result($stmt);

        //now check if the email match is affected
        if ($row = mysqli_fetch_assoc($result)) {

          //now check if the email match
          if ($email == $row['email']) {
            
            //store the session 
            $_SESSION['business_id'] = $row['id'];

          } else {
            die("Fatal error when matching the email");
          }
        } else {
          die("Fatal error");
        }
      }
    }
    
    //close the connection
    mysqli_close($conn);
  } else {
    //if they are not logged-in
    die("You must login");
  }
} else {
  //send error if the user try to get inside
  //the website without clicking the button
  die(http_response_code(404));
}
