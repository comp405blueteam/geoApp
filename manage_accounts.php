
<?php
	session_start();
	include_once("functions/accountManager.php");	
	
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
?>

<html>

<head><link href="StyleSheet.css" rel="stylesheet" type="text/css"></head>

<div class="container">
    
    <div id="sidebar">
        <div id="sidebarQuickSearch">
            <a href="quick_search.php"> <img style="max-width:100%; max-height:100%;" src="images/quick_search_label.png" /></a>
        </div>
        <div id="sidebarFullAnalysis">
            <a href="full_analysis.php"> <img style="max-width:100%; max-height:100%;" src="images/full_analysis_label.png" /></a>
        </div>
        <div id="sidebarReportsLogs">
            <a href="reports_logs.php"> <img style="max-width:100%; max-height:100%;" src="images/reports_logs_label.png" /></a>
        </div>
        <div id="sidebarUpperBlankSpace"></div>
        <div id="sidebarManageAccounts">
            <a href="manage_accounts.php"> <img style="max-width:100%; max-height:100%;" src="images/manage_accounts_label.png" /></a>
        </div>
        <div id="sidebarEditDatabase">
            <a href="edit_database.php"> <img style="max-width:100%; max-height:100%;" src="images/edit_database_label.png" /></a>
        </div>
        <div id="sidebarLowerBlankSpace"></div>
        <div id="sidebarUserInfo">CURRENT USER INFO</div>
    </div>
    
    <div id="applicationLogo">IMAGE</div>
    
    <div id="header">
    	<div id="headerTitle">Manage Accounts</div>
    	<div id="headerLogin">
            <a href="login.php">Login/Logout</a>
        </div>
    </div>
    
    <div id="content">
        <form name="contentForm" id="contentForm" method="POST" action="manage_accounts.php">
    	   <div id="contentLeftWindow">
    		  <div id="contentLeftWindowContents">
    			 User name:</br>
    			 <input name="userNameInput" id="userNameInput"></input></br></br>
    			 User email:</br>
    			 <input name="emailInput" id="emailInput"></input></br>
    			 <div id="itemContainer">
    				    <button name="runSearchButton" id="runSearchButton">Run Search</button>
    			 </div>
    		  </div>
    	   </div>
    	   <div id="contentRightWindow">
    		  <div id="contentRightWindowContents">
    		        IF USER DOES NOT EXIST</br>
                    &nbsp&nbsp<button name="createNewUserButton" id="createNewUserButton" type="submit">Create New User</button></br></br>
                    IF USER DOES EXIST</br>
                    &nbsp&nbsp<button name="userInfoButton" id="userInfoButton">Get/Update User Info</button></br>
                    &nbsp&nbsp<button name="resetUserPasswordButton" id="resetUserPasswordButton">Reset User Password</button></br>
                    &nbsp&nbsp<button name="deleteUserButton" id="deleteUserButton">Delete User</button></br>
                    <div id="lowerContentButtons">
                        <button name="clearResultsButton" id="clearResultsButton">Clear Results</button>
                    </div>
    		  </div>
    	   </div>
        </form>
    </div>

</div>

</html>
