<?php
    session_start();
    
    require_once("inc_functions.php");

    // if this is a post try to login
    if(isset($_POST['usernameInput'])){
            if(isset($_POST["passwordInput"])){
                    $accountManager->login();
            }
    }

    $title = 'Login';
    openHeader($title);

?>

<script>
    // request account function
    function request(){
            window.open('mailto:comp405blueteam@gmail.com');
    }
</script>

<?php closeHeader($title); ?> 

    
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

<?php outputFooter(); ?>
