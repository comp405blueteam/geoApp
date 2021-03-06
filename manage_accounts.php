<?php
/**
 * Displays the manage accounts page, and handles operations that are page specific
 * @author  George
 * @author  GUI: Paul and Tom
 */

//start session, include functions
require_once("inc_functions.php");

session_start();

$db = Db::getDbInstance();

// if it is a post check for the fields and createUser()
if ($_POST) {
    if (isset($_POST['userNameInput'])) {
        if (isset($_POST['emailInput'])) {

            $accountManager->createUser($_POST['userNameInput'], $_POST['emailInput']);
        }
    }
}

//displays user who match parameters
if (isset($_POST['name']) && isset($_POST['email'])) {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);

    displayUsers($name, $email);

    exit();
}

//updates, deactivates/activates users
if (isset($_POST['id']) && isset($_POST['action'])) {

    $uid = sanitize($_POST['id']);
    $action = sanitize($_POST['action']);

    if (!empty($uid) && !empty($action)) {
        $user = $db->getRow("SELECT * FROM user WHERE user_id = " . $uid);

        if (!empty($user)) {
            if ($action == 'update') {
                if (isset($_POST['id']) && isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email']) && isset($_POST['admin']) && isset($_POST['id'])) {
                    $user_id = sanitize($_POST['id']);
                    $fname = sanitize($_POST['fname']);
                    $lname = sanitize($_POST['lname']);
                    $email = sanitize($_POST['email']);
                    $admin = sanitize($_POST['admin']);


                    //want to call setters on user object
                    $fields = array('first_name', 'last_name', 'email', 'auth_level');
                    $values = array("'" . $fname . "'", "'" . $lname . "'", "'" . $email . "'", $admin);
                    $restriction = array('user_id', $user_id);

                    $accountManager->updateUser($fields, $values, $restriction);
                }
                echo 'Updated.';
            } else if ($action == 'delete') {
                $accountManager->deleteUser($uid);
                echo 'User has been deactivated.';
            } else if ($action == 'activate') {
                $accountManager->activateUser($uid);
                echo 'User has been deactivated.';
            } else {
                echo 'Invalid user action.';
            }
        } else {
            echo 'Invalid User';
        }
    }
    exit();
}



// check if the session auth is allowed  otherwise redirect to login page
//if($_SESSION['AUTH_LEVEL'] != 1){ header("Location: http://penguin.lhup.edu/~blueteam/geoApp/quick_search.php");}



$title = 'Manage Accounts';
openHeader($title);
?>


<script>
    //executes user search, calls ajax
    function searchAccounts() {
        var name = document.getElementById('userNameInput').value;
        var email = document.getElementById('emailInput').value;

        var dataString = {name: name, email: email};
        $.ajax({
            type: "POST",
            url: <?php echo "'" . BASE_URL . "manage_accounts.php'" ?>,
            data: dataString,
            async: false,
            cache: false,
            success: function(html)
            {
                document.getElementById('reportsLogsContentResults').innerHTML = html;
                return;
                //alert(html);
            }
        });
    }

    //executes user update, calls ajax
    function updateUser() {
        var id = document.getElementById('duid').value;
        var fname = document.getElementById('firstNameInput').value;
        var lname = document.getElementById('lastNameInput').value;
        var email = document.getElementById('emailInput').value;
        var admin = document.getElementById('adminSelect').value;

        var action = 'update';

        var dataString = {id: id, fname: fname, lname: lname, email: email, admin: admin, action: action};
        $.ajax({
            type: "POST",
            url: <?php echo "'" . BASE_URL . "manage_accounts.php'" ?>,
            data: dataString,
            async: false,
            cache: false,
            success: function(html)
            {
                //document.getElementById('resultsTextarea').innerHTML =  html;
                return;
                alert(html);
            }
        });

        self.close();
    }

    //delets user, calls ajax
    function deleteUser(act) {
        var id = document.getElementById('duid').value;
        var action = 'delete';

        if (act == 'activate') {
            action = 'activate';
        }

        var dataString = {id: id, action: action};
        $.ajax({
            type: "POST",
            url: <?php echo "'" . BASE_URL . "manage_accounts.php'" ?>,
            data: dataString,
            async: false,
            cache: false,
            success: function(html)
            {
                //alert(html);
                return;
                alert(html);
            }
        });

        self.close();

    }
    
    //displays the dialog to edit users
    function displayEdit(id) {
        var edit = window.open(<?php BASE_URL ?>"manage_accounts.php?uid=" + id, null, "height=300,width=340");
                edit.onload = function() {
                    edit.onunload = function() {
                        edit.opener.location.reload();
                    };
                }
    }



</script>    

<?php
//handles requetss to edit users, returns to ajax
if (isset($_GET['uid'])) {
    $uid = sanitize($_GET['uid']);

    $sql = "
            SELECT *
            FROM user
            WHERE user_id = " . $uid . "
            ";

    $user = $db->getRow($sql);

    if (empty($user)) {
        echo "<h2>User cannot be found.</h2>";
    } else {
        echo '<h2>User Editing</h2>';

        echo '<div>';
        echo '<form>';
        echo '<input id="duid" type="hidden" value="' . $user['user_id'] . '"/>';
        echo '<table id="selectTable">';
        echo '<tr><td>First Name:</td><td> <input name="firstNameInput" id="firstNameInput" value="' . $user['first_name'] . '"/></td></tr>';
        echo '<tr><td>Last Name:</td><td> <input name="lastNameInput" id="lastNameInput" value="' . $user['last_name'] . '"/></td></tr>';
        echo '<tr><td>Email:</td><td> <input name="emailInput" id="emailInput" value="' . $user['email'] . '"/></td></tr>';
        echo '<tr><td>Admin?:</td><td>' . getYesNoSelect($user['auth_level'], 'adminSelect') . '</td></tr>';
        echo '</table>';

        echo '<br/>';

        echo '<button type="button" onclick="updateUser();">Confirm Changes</button>&nbsp;&nbsp;&nbsp;';

        if ($user['active']) {
            echo '<button type="button" onclick="deleteUser(\'delete\');">Deactivate User</button>';
        } else {
            echo '<button type="button" onclick="deleteUser(\'activate\');">Activate User</button>';
        }

        echo '</form>';
        echo '</div>';
    }

    exit();
}

closeHeader($title);
?>



<div id=content>
    <form id="makeUser" name ="makeUser" method="POST" action="manage_accounts.php">
        <div id="accountBarName">
            Name:
            <input name="userNameInput" id="userNameInput" onchange="searchAccounts();"/>
        </div>
        <div id="accountBarEmail">
            Email:
            <input name="emailInput" id="emailInput" onchange="searchAccounts();"/>
        </div>
        <div id="createUser">
            <button name="createNewUserButton" id="createNewUserButton" type="button">Create User</button>
        </div>
    </form>


    <div id="mainContent">

        <div id="mainContentResults">
<?php displayUsers(); ?>
        </div>
    </div>

</div>

<?php outputFooter(); ?>
