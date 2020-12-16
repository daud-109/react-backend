<?php
/*This file will send auto email for business
**if they have to auto email checked.
*/

//Check if the patron is logged in
if (isset($_SESSION['patron_id'])) {

  //include this file to help us send email
  //require_once "./email.php";

  //today date
  $today_date = date("Y-m-d");

  //set seven days ago form the date of positive
  $seven_days_ago = date_create($date_of_positive);

  //now minus the seven days
  date_sub($seven_days_ago, date_interval_create_from_date_string("7 days"));

  //formate the date
  $seven_days_ago = date_format($seven_days_ago, "Y-m-d");

  //inner join query combine four tables
  //to send notification.
  $query = "SELECT DISTINCT patron.email 
            FROM (((business
            INNER JOIN notification
            ON business.id = notification.business_id)
            INNER JOIN spreadsheet
            on spreadsheet.business_id = business.id)
            INNER JOIN patron
            ON spreadsheet.patron_id = patron.id)
            WHERE notification.patron_id = ?
            AND business.alert = 0
            AND notification.positive_Date = ?
            AND spreadsheet.sheet_date BETWEEN ? AND ?
            ORDER BY patron.email";
  $stmt = mysqli_stmt_init($conn);

  //if the query failed
  if (!mysqli_stmt_prepare($stmt, $query)) {
    //Fatal error with the four table select query"
    die(http_response_code(409));
  } else {

    //bind the pass value
    mysqli_stmt_bind_param($stmt, "isss", $patron_id, $date_of_positive, $seven_days_ago, $today_date);

    //check if the statement executed
    if (mysqli_stmt_execute($stmt)) {

      //get the result
      $result = mysqli_stmt_get_result($stmt);

      //declare increment variable
      $i = 0;

      //send from
      $mail->setFrom('phpseniorproject@gmail.com', 'COVID-19 Tracker');

      //subject
      $mail->Subject = 'COVID-19 Alert at';

      //use the while loop to get the contact
      while ($auto_row = mysqli_fetch_assoc($result)) {

        //email setting
        $mail->addAddress($auto_row['email']);
        $mail->addBCC($auto_row['email']);
        //message
        
      }
      $mail->Body = "This is an automated alert that was sent because someone that has been to the same business location as you recently has reported positive for COVID-19 on the following date  " . $date_of_positive;
      //send the mail
      if ($mail->send()) {
        //if email is send
        echo "Email was send";
        die(http_response_code(200));
        //if the email is not send
      } else {
        echo "Email was not send";
      }
    } else {
      echo "Statement did not execute";
    }
  }
} else {
  //send error message
  die("Please Log-in");
}
