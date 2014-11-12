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
        
        <link rel="apple-touch-icon" sizes="57x57" href="../images/icons/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="114x114" href="../images/icons/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="72x72" href="../images/icons/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="144x144" href="../images/icons/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="60x60" href="../images/icons/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="120x120" href="../images/icons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="76x76" href="../images/icons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="152x152" href="../images/icons/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="../images/icons/apple-touch-icon-180x180.png">
        <link rel="icon" type="image/png" href="../images/icons/favicon-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="../images/icons/favicon-160x160.png" sizes="160x160">
        <link rel="icon" type="image/png" href="../images/icons/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="../images/icons/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="../images/icons/favicon-32x32.png" sizes="32x32">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-TileImage" content="../images/icons/mstile-144x144.png">
 
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
                    if(isset($_SESSION['AUTH_LEVEL'])) {
                        displayLink('<a href="quick_search.php"> <img style="max-width:100%; max-height:100%;" src="images/quick_search_label.png" /></a>'); 
                    }
                ?>
            </div>
            <div id="sidebarFullAnalysis">
                <?php
                    if(isset($_SESSION['AUTH_LEVEL'])) {
                        displayLink('<a href="full_analysis.php"> <img style="max-width:100%; max-height:100%;" src="images/full_analysis_label.png" /></a>'); 
                    }
                ?>
            </div>
            <div id="sidebarReportsLogs">
                <?php
                    if(isset($_SESSION['AUTH_LEVEL'])) {
                        displayLink('<a href="reports_logs.php"> <img style="max-width:100%; max-height:100%;" src="images/reports_logs_label.png" /></a>'); 
                    }
                ?>
            </div>
            <div id="sidebarUpperBlankSpace"></div>
            <div id="sidebarManageAccounts">
                <?php
                    if(isset($_SESSION['AUTH_LEVEL'])) {
                        if($_SESSION['AUTH_LEVEL'] == 1) {
                            displayLink('<a href="manage_accounts.php"> <img style="max-width:100%; max-height:100%;" src="images/manage_accounts_label.png" /></a>'); 
                        }
                    }
                ?>
            </div>
            <div id="sidebarEditDatabase">
                <?php
                    if(isset($_SESSION['AUTH_LEVEL'])) {
                        if($_SESSION['AUTH_LEVEL'] == 1) {
                            displayLink('<a href="edit_database.php"> <img style="max-width:100%; max-height:100%;" src="images/edit_database_label.png" /></a>'); 
                        }
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
