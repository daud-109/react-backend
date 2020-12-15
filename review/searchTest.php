<?php
/*Display all of the business so the 
**user can select a business to check
**for the review of the business
*/

//declare variable here
$search_by = $search_for = '';


//Post variable here and fill in the post variable name
$search_by = htmlspecialchars($_POST['search_by']);
$search_for = htmlspecialchars($_POST['search_for']);

//if data is empty
if (empty($search_by) || empty($search_for)) {
  die("Please enter all of the value");
} else {
  //hold these variable
  session_start();

  if (isset($_SESSION['search_by'])) {
    unset($_SESSION['search_by']);
  }

  if (isset($_SESSION['search_for'])) {
    unset($_SESSION['search_for']);
  }

  $_SESSION['search_by'] = $search_by;
  $_SESSION['search_for'] = $search_for;
}
