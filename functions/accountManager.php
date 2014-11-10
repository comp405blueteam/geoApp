
<?php
session_start();

include_once("../../constants.php");	
include_once("db.php");
include_once("user.php");
include_once("utils.php");


Class AccountManager{

	

	public function addUserToDb(User $user){ 
		
		$db = Db::getDbInstance();		
		
		// add a user to database function
		// create sql statement for updating database
		
		//echo "Inserting user". $user->accountId . $user->email . $user->firstName . $user->lastName . md5($user->password) . $user->accountType;
		$sql =
		"
		INSERT INTO user
		(user_id, email, first_name, last_name, password, auth_level, active)
		VALUES
		(
		'" . $user->accountId . "',
		'" . $user->email . "',
		'" . $user->firstName . "',
		'" . $user->lastName . "',
		'" . md5($user->password) . "',
		'" . $user->accountType . "',
		'1'
		);
		";
		//echo "about to Qury db..  " . $sql;		
		// update db
		$db->insert($sql);
		
		echo "  Login info is: " . $user->lastName . $user->accountId . "  " . $user->password;
	}

	public function login(){
	 	
		$db = Db::getDbInstance();
		
		//Login Function
		
		// get creds from post
		$userName = sanitize($_POST['usernameInput']);
		$userPass = md5(sanitize($_POST['passwordInput']));
		
		// get number for id
		$uid = preg_replace('/\D/', '', $userName);
		
		// get the last name
		$lname = preg_replace('/[0-9]/', '', $userName);
		
		// create sql to auth user
		$sql =
		"
		SELECT first_name, auth_level, user_id
		FROM user
		WHERE user_id = '" . $uid . "'
		AND last_name = '" . $lname . "'
		AND password = '" . $userPass . "';
		";

		// get result
		$result=$db->getRset($sql);
		
		// echo if invalid
		if(empty($result)){
			
			//header( "Location: http://penguin.lhup.edu/~blueteam/geoApp/login.php" );
			echo "
			<script type='text/javascript'>
				function Inval(){
					alert('Invalid User/Pass');
				}
				Inval();
			</script>
			<noscript>Invalid User or Pass</noscript>
			";
			//header("Location: http://penguin.lhup.edu/~blueteam/geoApp/login.php");
		}
		else {
			//echo "Setting sessions";
			// set session variables
			$_SESSION['AUTH_LEVEL'] = $result[0]['auth_level'];
			$_SESSION['NAME'] = $result[0]['first_name'];
			$_SESSION['UID'] = $result[0]['user_id'];
			
			// redirect to main page
			header("Location: ".BASE_URL."quick_search.php");
		}
		
				
	}	
    
	public function logout(){
		//Logout Function
		session_destroy();
		
		//redirect to login
		header("Location: ".BASE_URL."login.php");
	}
    
	public function requestAccount(){
		// Request Account Function
		// This function will open a new mailto window to request a account
		echo "
		<script>
			function sendEmail(){
				window.open('mailto:comp405blueteam@gmail.com')
			}
			sendEmail();
		</script>
		<noscript>Please turn javascript on</noscript>
		";
	}
    
	public function createUser(){
		// create user function		
		$user = new user($_POST['userNameInput'], $_POST['emailInput']);
		//echo "User Atribs: " . $user->accountId . $user->email . $user->lastName . $user->firstName . $user->password;
		
		// add a new user to db
		self::addUserToDb($user);
		//echo "User was added to DB";

		// return the newly created user
		return $user;				
	}

	public function forgotPassword(){
		// forgot password function
		echo "forgot password called";
	}

	public function acceptAccount($name, $email){
		// accept account function
		// make a new user with supplied name and email
		$user = new user($name, $email); 
		
		// add user to db
		addUserToDb($user);
	}

	public function resetPassword(){
		//reset password function
		echo "reset password called";
	}

	public function deleteUser(){
		//delete user function
		echo "delete user called";
	}

	public function archiveReports(){
		//archive reports function
		echo "archive reports called";
	}

	public function grantAdmin($user){
		$db = Db::getDbInstance();
		
		// grant admin function
		// make sql statement
		$sql =
		"
		UPDATE user
		SET auth_level
		WHERE user_id = '" . $user->$accountId . "';
		";
		
		// update db with new status
		$db->insert($sql);
		
		// change users priv
		$user->$accountType = 1;
	}

}
	$accountManager= new accountManager();
?>
