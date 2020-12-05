<?php
/*This file will send email to all the patron that 
**are register to that business. 
*/

//Check if the user have click the post method button
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  //Start session
  session_start();

  //check if user is logged-in
  if (isset($_SESSION['owner_id'])) {

    //check which business is selected
    if (isset($_SESSION['business_id'])) {

      //include the file
      require_once 'email.php'; //this will help with sending email     
      require_once '../../mysqlConn.php';
      require_once '../../function.php';

      //declare the variable
      $subject = $message = "";

      //Post variables 
      $subject = htmlspecialchars($_POST['subject']);
      $message = htmlspecialchars($_POST['message']);

      //check if the date is empty
      if (empty($subject) || empty($message)) {
        //make sure the user enter the value
        die("Make sure all the value are enter");
      } else {

        //store the session value
        $business_id = $_SESSION['business_id'];

        //this file wil help to send business info
        require_once 'businessInfoEmail.php';

        //This query will get the email of the patron.
        $query = "SELECT DISTINCT s.patron_id, p.email FROM spreadsheet  AS s, patron AS p WHERE s.business_id = ? AND s.patron_id = p.id ORDER BY s.patron_id";
        $stmt = mysqli_stmt_init($conn);

        //if the query does not run
        if (!mysqli_stmt_prepare($stmt, $query)) {
          die("Fatal error the spreadsheet select query did not run");
        } else {

          //bind the pass value
          mysqli_stmt_bind_param($stmt, "i", $business_id);

          //Check if the statement executed
          if (mysqli_stmt_execute($stmt)) {

            //get the result 
            $result = mysqli_stmt_get_result($stmt);

            //test for increment
            $i = 0;

            //also need to check if the there was no data in the databaseeeeee


            //use the loop to get all the patron email
            while ($row = mysqli_fetch_assoc($result)) {
              //email setting
              $mail->setFrom('phpseniorproject@gmail.com', 'Email Test');
              $mail->addAddress($row['email']);
            }

            //subject and message of the email
            $mail->Subject = $subject . " " . $business_row['name'];
            $mail->Body    = $message;

            //send the mail
            if ($mail->send()) {
              echo "Email was send";
            } else {
              echo "Email was not send";
            }
          } else {
            echo "Statement was not executed";
          }
        }
      }

      //free the memory
      mysqli_stmt_free_result($stmt);

      //close the statement
      mysqli_stmt_close($stmt);

      //close the connection
      mysqli_close($conn);

      //display error if no business was selected
    } else {
      die("Select a business");
    }

    //display an error if the user is not logged-in 
  } else {
    die("You must login");
  }
} else {
  //send error if the user try to get inside
  //the website without clicking the button
  die(http_response_code(404));
}
