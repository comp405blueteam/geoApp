<?php
session_start();
include_once "user.php";
include_once "db.php";
include_once "utils.php";
class accountManager{
	
	function addUserToDb($user){
		// add a user to database function
		// create sql statement for updating database
		$sql =
		"
		INSERT INTO user
		(user_id, email, first_name, last_name, password, auth_level, active)
		VALUES
		(
		'" . ($user->$accountId) . "',
		'" . ($user->$email) . "',
		'" . ($user->$firstName) . "',
		'" . ($user->$lastName) . "',
		'" . (md5($user->$password)). "',
		'" . ($user->$accountType) . "',
		'1'
		);
		";
		
		// update db
		$db->insert($sql);
				
	}

	public function login(){
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
		if($result == "SQL QUERY FAILURE"){
			echo "
			<script>
				window.alert('Invalid User/Pass');
			</script>
			<noscript>Invalid User or Pass</noscript>
			";
		}
		else {
			// set session variables
			$_SESSION['AUTH_LEVEL'] = $result[0]['auth_level'];
			$_SESSION['NAME'] = $result[0]['firstname'];
			$_SESSION['UID'] = $result[0]['user_id'];
			
			// redirect to main page
			header("Location: http://penguin.lhup.edu/~blueteam/geoApp/quick_search.php");
		}
		
				
	}	
    
	public function logout(){
		//Logout Function
		session_destroy();
		
		//redirect to login
		header("Location: http://www.penguin.lhup.edu/~blueteam/geoApp/login.php");
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
		
		// add a new user to db
		addUserToDb($user);
		
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
?>
