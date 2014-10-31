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
    	<div id="headerTitle">Reports Logs</div>
    	<div id="headerLogin">
            <a href="login.php">Login/Logout</a>
        </div>
    </div>
    
    <div id="content">
        <form name="contentForm" id="contentForm">
    	   <div id="reportsLogsReportID">
    		  Report ID: <input name="reportIdInput" id="reportIdInput"></input>
    	   </div>
    	   <div id="reportsLogsElement">
    		  Element: <input name="elementInput" id="elementInput"></input>
    	   </div>
    	   <div id="reportsLogsDate">
    		  Date: <input name="dateInput" id="dateInput"></input>
    	   </div>
    	   <div id="reportsLogsSearchButton">
    		  <button name="searchButton" id="searchButton">Search</button>
    	   </div>
    	   <div id="reportsLogsContent">
    		  <div id="reportsLogsContentResults">
    			 <textarea disabled name="resultsTextarea" id="resultsTextarea" style="resize:none; overflow-y:auto; overflow-x:auto; width:100%; height:99%;";>RESULTS - USERS CANNOT EDIT TEXTFIELD</textarea>
    		  </div>
    		  <div id="lowerContentButtons">
    			 <button name="myReportsButton" id="myReportsButton">My Reports</button>&nbsp&nbsp<button>Clear Results</button>
    		  </div>
    	   </div>
        </form>
    </div>

</div>

</html>