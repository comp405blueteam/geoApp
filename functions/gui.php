<?php

function header_out($title = "PHP Test"){
    echo "<html>";

    echo "<head>";
    
    echo "<title>".$title."</title>";
    
    echo "</head>";
    
    echo "<body bgcolor = 'gray'>";
}

function openHeader($title){
    ?>
        <html>

        <head>
            
        <title><?php echo $title ?> - CIT</title>

        <link href="StyleSheet.css" rel="stylesheet" type="text/css">
 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        
    <?php
}

function closeHeader($title){
    ?>
        </head>
        
        <div class="container">

        <div id="sidebar">
            <div id="sidebarQuickSearch">
                <?php
                    if($_SESSION['AUTH_LEVEL'] == 0) {
                        displayLink('<a href="quick_search.php"> <img style="max-width:100%; max-height:100%;" src="images/quick_search_label.png" /></a>'); 
                    }
                ?>
            </div>
            <div id="sidebarFullAnalysis">
                <?php
                    if($_SESSION['AUTH_LEVEL'] == 0) {
                        displayLink('<a href="full_analysis.php"> <img style="max-width:100%; max-height:100%;" src="images/full_analysis_label.png" /></a>'); 
                    }
                ?>
            </div>
            <div id="sidebarReportsLogs">
                <?php
                    if($_SESSION['AUTH_LEVEL'] == 0) {
                        displayLink('<a href="reports_logs.php"> <img style="max-width:100%; max-height:100%;" src="images/reports_logs_label.png" /></a>'); 
                    }
                ?>
            </div>
            <div id="sidebarUpperBlankSpace"></div>
            <div id="sidebarManageAccounts">
                <?php
                    if($_SESSION['AUTH_LEVEL'] == 1) {
                        displayLink('<a href="manage_accounts.php"> <img style="max-width:100%; max-height:100%;" src="images/manage_accounts_label.png" /></a>'); 
                    }
                ?>
            </div>
            <div id="sidebarEditDatabase">
                <?php
                    if($_SESSION['AUTH_LEVEL'] == 1) {
                        displayLink('<a href="edit_database.php"> <img style="max-width:100%; max-height:100%;" src="images/edit_database_label.png" /></a>'); 
                    }
                ?>
            </div>
            <div id="sidebarLowerBlankSpace"></div>
            <div id="sidebarUserInfo">
            
                <?php
                    
                    if(!empty($_SESSION['NAME'])){
                        echo 'Welcome '.$_SESSION['NAME'];
                    }   
                    
                ?>
                
            </div>
        </div>

        <div id="applicationLogo">IMAGE</div>

        <div id="header">
            <div id="headerTitle"><?php echo $title; ?></div>
            <div id="headerLogin">
                
                    
                    <?php
                    
                    if(!empty($_SESSION['UID'])){
                        echo '<a href="logout.php"> Logout';
                    }else{
                        echo '<a href="login.php"> Login';
                    }    
                    
                    ?>
                
                </a>
            </div>
        </div>
    <?php
}

function outputFooter(){
    echo "</div>";
    
    echo "</html>";
}


function displayLink($link) {
    echo $link;
}

?>
