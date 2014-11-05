
<?php
	session_start();
	include_once("functions/accountManager.php");
        require_once("functions/gui.php");
	
	// if it is a post check for the fields and createUser()
	if($_POST){
		if(isset($_POST['userNameInput'])){
			if(isset($_POST['emailInput'])){
				$accountManager->createUser();
			}
		}
	}
	// check if the session auth is allowed  otherwise redirect to login page
	//if($_SESSION['AUTH_LEVEL'] != 1){ header("Location: http://penguin.lhup.edu/~blueteam/geoApp/quick_search.php");}
        
        $title = 'Manage Accounts';
        openHeader($title);
        closeHeader($title);
?>


    
    <div id="content">
        <form name="contentForm" id="contentForm" method="POST" action="manage_accounts.php">
    	   <div id="contentLeftWindow">
    		  <div id="contentLeftWindowContents">
    			 User name:<br/>
    			 <input name="userNameInput" id="userNameInput"/><br/><br/>
    			 User email:<br/>
    			 <input name="emailInput" id="emailInput"/><br/>
    			 <div id="itemContainer">
    				    <button name="runSearchButton" id="runSearchButton">Run Search</button>
    			 </div>
    		  </div>
    	   </div>
    	   <div id="contentRightWindow">
    		  <div id="contentRightWindowContents">
    		        IF USER DOES NOT EXIST<br/>
                    &nbsp&nbsp<button name="createNewUserButton" id="createNewUserButton" type="submit">Create New User</button><br/><br/>
                    IF USER DOES EXIST<br/>
                    &nbsp&nbsp<button name="userInfoButton" id="userInfoButton">Get/Update User Info</button><br/>
                    &nbsp&nbsp<button name="resetUserPasswordButton" id="resetUserPasswordButton">Reset User Password</button><br/>
                    &nbsp&nbsp<button name="deleteUserButton" id="deleteUserButton">Delete User</button><br/>
                    <div id="lowerContentButtons">
                        <button name="clearResultsButton" id="clearResultsButton">Clear Results</button>
                    </div>
    		  </div>
    	   </div>
        </form>
    </div>

<?php outputFooter(); ?>
