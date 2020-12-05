<?php
/*This file will check which patron were presented
**at the business location base on the starting date
**and end date.Then look for the email of the patron. 
*/

//Check if the user have click the post method button
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  //Start session
  session_start();

  //check if user is logged-in
  if (isset($_SESSION['owner_id'])) {

    //check which business is selected
    if (isset($_SESSION['business_id'])) {

      //include the file to connect with mysql 
      require_once 'email.php';
      require_once '../../mysqlConn.php';
      require_once '../../function.php';

      //declare the variable
      $starting_date = $end_date = "";


      //Post variables 
      $starting_date = htmlspecialchars($_POST['starting_date']);
      $end_date = htmlspecialchars($_POST['end_date']);
      $message = htmlspecialchars($_POST['message']);

      //check if the date is empty
      if (empty($starting_date) || empty($end_date)) {
        //make sure the user enter the value
        die("Make sure all the value are enter");
      } else {

        //store the session value
        $business_id = $_SESSION['business_id'];

        //this file wil help to send business info
        require_once 'businessInfoEmail.php';

        //This query will get the email of the patron.
        $query = "SELECT DISTINCT s.patron_id, p.email FROM spreadsheet  AS s, patron AS p where s.business_id = ? and s.patron_id = p.id and s.sheet_date BETWEEN ? and ? ORDER BY s.patron_id";
        $stmt = mysqli_stmt_init($conn);

        //if the query does not run
        if (!mysqli_stmt_prepare($stmt, $query)) {
          die("Fatal error the spreadsheet select query did not run");
        } else {

          //bind the pass value
          mysqli_stmt_bind_param($stmt, "iss", $business_id, $starting_date, $end_date);

          //Check if the statement executed
          if (mysqli_stmt_execute($stmt)) {

            //get the result 
            $result = mysqli_stmt_get_result($stmt);

            //use for incrementing for the while loop
            $i = 0;

            //also need to check if the there was no data in the databaseeeeee


            //use this loop to get all the contact
            while ($row = mysqli_fetch_assoc($result)) {

              //email setting
              $mail->setFrom('phpseniorproject@gmail.com', 'Email Test');
              $mail->addAddress($row['email']);
            }

            //subject
            $mail->Subject = 'Website subject' . " " . $business_row['name'];

            //if the business want to send a message
            if ($message) {
              $mail->Body = $message;
            } else {
              $mail->Body = "This is website test.";
            }

            //send the mail
            if ($mail->send()) {
              //if email is send
              echo "Email was send";

              //if the email is not send
            } else {
              echo "Email was not send";
            }

            //if the query did not executed
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
