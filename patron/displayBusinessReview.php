<?php
/*This file will display the business review
**with access to review if the patron have the
**authorization.
*/
header('Content-Type: application/json');

//start session
session_start();

if(isset($_SESSION['patron_id'])){
  
}