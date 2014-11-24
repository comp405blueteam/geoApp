<?php
/**
 * Handles all account management operations
 * @author  Justin and Alex
 * @author  George
 */

Class AccountManager{

    public function forceLogin(){
		$db = Db::getDbInstance();

        // check if session is set
        if(!isset($_SESSION)){
            header("Location: ".BASE_URL."login.php");
        }
        // create sql to auth user
		$sql =
		"
		SELECT first_name, auth_level, user_id, active
		FROM user
		WHERE user_id = '" . $_SESSION['UID'] . "'
		AND first_name = '" . $_SESSION['NAME'] . "'
		AND auth_level = '" . $_SESSION['AUTH_LEVEL'] . "'
		AND active = '1';
		";

        // query db
		$result=$db->getRset($sql);
		
		// check if invalid
		if(empty($result)){
            header("Location: ".BASE_URL."login.php");
        }
        return;
        
    }
        
	public static function getAMInstance(){
            global $accountManager;
            return $accountManager;
        }

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

	public function login($userName, $userPass){
	 	
		$db = Db::getDbInstance();
		
		//Login Function
		
		// get creds from post
		$userName = sanitize($userName);
		if(preg_match('/^[a-f0-9]{32}$/', $userPass)){
			$userPass = sanitize($userPass);
            		//echo "It matched MD5 : " . $userPass;
        	}else{
			//echo "It didn't match. It was " . $userPas . " and it should be ";
			$userPass = md5(sanitize($userPass));
            		// echo " " . $userPass;
			//$userPass = md5($userPass);
		}
		// get number for id
		$uid = preg_replace('/\D/', '', $userName);
		
		// get the last name
		$lname = preg_replace('/[0-9]/', '', $userName);
		
		// create sql to auth user
		$sql =
		"
		SELECT first_name, auth_level, user_id, active
		FROM user
		WHERE user_id = '" . $uid . "'
		AND last_name = '" . $lname . "'
		AND password = '" . $userPass . "'
		AND active = '1';
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
		unset($_SESSION['UID']);
		unset($_SESSION['NAME']);
		unset($_SESSION['AUTH_LEVEL']);
		
		
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
    
	public function createUser($userName, $userEmail){
		// create user function		
		$user = new user($userName, $userEmail);
		//echo "User Atribs: " . $user->accountId . $user->email . $user->lastName . $user->firstName . $user->password;
		
		// add a new user to db
		self::addUserToDb($user);
		//echo "User was added to DB";

		// return the newly created user
		return $user;				
	}
        
        public function updateUser($fields, $values, $restriction){
            $db = Db::getDbInstance();
            
            if(count($fields) != count($values)){
                return;
            }
            
            $sql = 
            "
            UPDATE user
            SET
            ";
            
            for($i=0;$i<count($fields);$i++){
                if($i==0){
                    $sql .= $fields[$i]."=".$values[$i];
                    continue;
                }
                
                $sql .= ",".$fields[$i]."=".$values[$i];
            }
            
            $sql .= " WHERE ".$restriction[0]."=".$restriction[1];
                       
            echo $sql;
            
            $db->runSQL($sql); 
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
		// forgot password function
		echo "
		<script>
		function sendEmail(){
		window.open('mailto:$user->email')
		}
		sendEmail($user->password);
		</script>
		<noscript>Please turn javascript on</noscript>
		";
	}

	public function deleteUser($uid){
            

		$db = Db::getDbInstance(); 

		$sql = 
                "
                UPDATE user
                SET active = 0
                WHERE user_id = $uid
                LIMIT 1
                ";
                
                $db->runSQL($sql, true);
	}
        
        public function activateUser($uid){
            

		$db = Db::getDbInstance(); 

		$sql = 
                "
                UPDATE user
                SET active = 1
                WHERE user_id = $uid
                LIMIT 1
                ";
                
                $db->runSQL($sql, true);
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
        
        function getUsers($name = "", $email = ""){
            $db = Db::getDbInstance();
            
            $sql = 
            "
            SELECT * 
            FROM user
            WHERE user_id = user_id
            ";
            
            if(!empty($name)){
                $sql .= 
                "
                AND (first_name LIKE '".$name."' OR last_name LIKE '".$name."')
                ";
                
            }
            
            if(!empty($email)){
                $sql .= 
                "
                AND email LIKE '".$email."'
                ";
            }
            
            $sql .= 
            "
            ORDER BY last_name
            ";
            
            return $db->getRset($sql);
        }

}
	$accountManager= new accountManager();
?>
