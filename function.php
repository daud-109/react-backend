<?php

function destroySession(){
  $_SESSION=array();

  if (session_id() != "" || isset($_COOKIE[session_name()])){
    setcookie(session_name(), '', time()-2592000, '/');
  }
  session_destroy();
}

function validateEmail($field){
  if ($field == "") {
    return "No Email was entered";
  }
  else if (!preg_match("/^[a-z\d\._-]+@([a-z\d]+\.)+[a-z]{2,6}$/i", $field)){
      return "The Email address is invalid.<br>";
  }
  return true;
}


?>