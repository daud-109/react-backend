<?php 

function validate_email($email){
  //$filter_email = filter_var($email, FILTER_SANITIZE_EMAIL);
  if (filter_var($email,FILTER_VALIDATE_EMAIL)){
    echo "This is correct email\n";
  }
}

function validateEmail($field){
  if ($field == "") return "No Email was entered<br>";
  else if (!preg_match("/^[a-z\d\._-]+@([a-z\d]+\.)+[a-z]{2,6}$/i", $field))
      return "The Email address is invalid.<br>";
  return "good";
	}

$email = "h1jhon@cool.com";
// $x = strip_tags($email);
// echo $x . "\n";
// echo "This is addslash function: " . addslashes($x). "\n";
echo validate_email($email) . "\n";
echo validateEmail($email);

// if(strpos("cool man", " ")){
//   echo "It contain a space.\n";
// }
?>