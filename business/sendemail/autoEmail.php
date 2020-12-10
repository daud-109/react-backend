<?php
/*This file will send auto email for business
**if they have to auto email checked.
*/

//Check if the patron is logged in
if (isset($_SESSION['patron_id'])) {

  //include this file to help us send email
  require_once "./email.php";

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
  $query = "SELECT DISTINCT patron.email, business.name 
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
            AND spreadsheet.sheet_date BETWEEN ? AND ?";
  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $query)) {
    die("Fatal error with the four table select query");
  }else {
    
  }
} else {
  //send error message
  die("Please Log-in");
}
