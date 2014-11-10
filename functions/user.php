
<?php

include_once("../../constants.php");
include_once("db.php");
include_once("utils.php");



class User{

	public $userName;
	public $email;
	public $firstName;
	public $lastName;
	public $password;
	public $accountType;
	public $accountId;
	
	function User($rName, $eMail){
	
		$db=Db::getDbInstance();		
	
		// set the users name
        	// get two string from the name first and last
        	$delimitedString = explode(' ', $rName);
        	$fName = $delimitedString[0];
        	$lName = $delimitedString[1];
        	
		// sanitize and add
        	$fName = sanitize($fName);
        	$lName = sanitize($lName);
		$this->firstName = $fName;
        	$this->lastName = $lName;   	
		
		// set the users email
		$this->email = $eMail;
		
		
		// set the account to a standard account
		$this->accountType = 0;
		
		// get a new id for user
		// query db for max id +1
		$sql =
		"
		SELECT MAX(user_id)
		FROM user;
		"; 
		
		$newId = $db->getVal($sql);
		
		$newId = $newId + 1;

		// set the Id
		$this->accountId = $newId;
		
		// set account userName as the rName + id
		$this->userName = $lName . $newId;
		
		// set password to the userName!temp 
		$this->password = $this->lastName . "!temp";
	}
	public function getName(){
		//get the users name
		return $this->name;
	}
    
	public function getUserType(){
		//return the the users type
		return $this->accountType;
	}
    
	public function getUserId(){
		//return the users Id
		return $this->accountId;
	}
}
?>
