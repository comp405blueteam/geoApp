<?php
/**
 * Index page, same as login.php
 * @author  Justin
 * @author  GUI: Paul and Tom
 */
    session_start();
    
    require_once("inc_functions.php");

    // if this is a post try to login
    if(isset($_POST['usernameInput'])){
            if(isset($_POST["passwordInput"])){
                    
$accountManager->login($_POST['usernameInput'],$_POST['passwordInput']);
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
<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/md5.js"></script>
<script>
        function hashPass(){
            var pass = document.getElementById("passwordInput").value;
            document.getElementById("passwordInput").value = CryptoJS.MD5(pass).toString();
        }
</script>
<noscript>
Please Turn On Javascript!
</noscript>
<?php closeHeader($title); ?> 

    
    <div id="content">
        <form name="contentForm" id="contentForm" method="POST" action="login.php">
    	   <div id="contentLogin">
    		  Username: <input name="usernameInput" id="usernameInput" /><br/><br/>
    		  Password: <input name="passwordInput" id="passwordInput" type=password /><br/><br/><br/>
    		  <input name="loginButton" id="loginButton" type="image" src="images/buttons/login_button.png" onclick="hashPass();" /><br/><br/>
    		  <input name="forgotPass" id="forgotPass" type="image" src="images/buttons/forgot_button.png" /><br/><br/>
    		  <input name="requestAccountButton" id="requestAccountButton" type="image" src="images/buttons/request_button.png" onclick="request();" /><br/>
    	   </div>
        </form>
    </div>

<?php outputFooter(); ?>
