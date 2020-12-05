<?php
 //include the file
 require_once 'email.php'; //this will help with sending email
 //require_once 'test1.php'; //this file wil help to send business info
 
 require_once '../../mysqlConn.php';
 require_once '../../function.php';

 //declare the variable
 $subject = "test"; $message = "test";

 //check if the date is empty
 if (empty($subject) || empty($message)) {
   //make sure the user enter the value
   die("Make sure all the value are enter");
 } else {

   //store the session value
   $business_id = 1;

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

         //this array will hold all the email patron
         // $to_array[$i] = $row['email'];
         // $i++;
         
       //email setting
       $mail->setFrom('phpseniorproject@gmail.com', 'Email Test');
       $mail->addAddress($row['email']);

       //subject and message of the email
       $mail->Subject = $subject . " " . $business_row['name'];
       $mail->Body    = $message;

       //send the mail
       if ($mail->send()) {
         echo "Email was send";
       } else {
         echo "Email was not send";
       }
       }

       //this hold all of the email of the patron
       //$to = implode(",", $to_array);

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
