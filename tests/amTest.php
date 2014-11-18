<?php
  include_once("functions/accountManager.php");
  include_once(inc_functions.php);
  
  function testLoginLogout(){
  
  // make login info
  $username = "104Heins";
  $password = "password";
  $hashedPass = md5(password);
  
  
  // login with the info
  $accountManger->login($username, $password);
  
  // check that the session is set
  if(!isset($_SESSION['UID']) || !isset($_SESSION['NAME']) || !isset($_SESSION['AUTH_LEVEL'])){
	  echo "You are not logged in!"
  }
  
  // check contents of the sesssion
  if($_SESSION['UID'] != 104 || $_SESSION['NAME'] != "Zach" || $_SESSION['AUTH_LEVEL'] != 1){
 	 echo "Invalid Session Information";
  }
  
  // logout
  $accountManger->logout();
  
  // check that session was destroyed
    if(isset($_SESSION['UID']) || isset($_SESSION['NAME']) || isset($_SESSION['AUTH_LEVEL'])){
  		echo "The session was not terminated."
  }
  
  // login with hashed info
  $accountManger->login($username, $hashedPass);
  
  // check that the session is set
  if(!isset($_SESSION['UID']) || !isset($_SESSION['NAME']) || !isset($_SESSION['AUTH_LEVEL'])){
  	echo "You are not logged in!"
  }
  
  // check contents of the sesssion
  if($_SESSION['UID'] != 104 || $_SESSION['NAME'] != "Zach" || $_SESSION['AUTH_LEVEL'] != 1){
 	 echo "Invalid Session Information";
  }
    
  // logout
  $accountManger->logout();
  
  // check that session was destroyed
    if(isset($_SESSION['UID']) || isset($_SESSION['NAME']) || isset($_SESSION['AUTH_LEVEL'])){
  		echo "The session was not terminated."
  }
  
  
}  
  
  
  
  
  
  ?>
