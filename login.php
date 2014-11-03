
<?php
	
	include_once("functions/accountManager.php");
	session_start();
	
	// if this is a post try to login
	if(isset($_POST['usernameInput'])){
		if(isset($_POST["passwordInput"])){
			$accountManager->login();
		}
	}

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
    	<div id="headerTitle">Login</div>
    	<div id="headerLogin">
            <a href="login.php">Login/Logout</a>
        </div>
    </div>
    <script>
	// request account function
	function request(){
		window.open('mailto:comp405blueteam@gmail.com');
	}
    </script>
    <div id="content">
        <form name="contentForm" id="contentForm" method="POST" action="login.php">
    	   <div id="contentLogin">
    		  Username: <input name="usernameInput" id="usernameInput" /><br/><br/>
    		  Password: <input name="passwordInput" id="passwordInput" type=password /><br/><br/><br/>
    		  <button name="loginButton" id="loginButton" type="submit">Login</button><br/><br/>
    		  <button name="forgotPass" id="forgotPass">Forgot Password</button><br/><br/>
    		  <button name="requestAccountButton" id="requestAccountButton" onclick="request();">Request Account</button><br/>
    	   </div>
        </form>
    </div>

</div>

</html>
