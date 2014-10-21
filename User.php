<?php
class User{
	
	public $userName;
	public $email;
	public $name;
	public $password;
	public $accountType;
	public $accountId;
	
	public function getName(){
		//get the users name
		echo "getName Called";
	}
    
	public function getUserType(){
		//return the the users type
		echo "getUserType Called";
	}
    
	public function getUserId(){
		//return the users Id
		echo "getUserId Called";
	}
}
?>
