<?php 
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Constants.php");

$account = new Account($connection);

    if(isset($_POST["submitButton"]))
    {
     
        $password =  FormSanitizer::sanitizeFormPassword($_POST["password"]);
        $userName =  FormSanitizer::sanitizeFormUsername($_POST["username"]);


        $success = $account->login($userName,$password);

        if($success)
        {
            $_SESSION["userLoggedIn"] = $userName;

            header("Location: index.php");
        }
    }

    function getInputValue($name)
    {
        if(isset($_POST[$name]))
        {
            echo $_POST[$name];
        }
    }


?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="assets/style/style.css"/>
    </head>
    <body>
        <div class="signInContainer">
            <div class="column">

                <div class="header">
                    <img src="assets/images/2a7a5a5a2eb543d3cf6a38bf598291ed.png" title="Logo" alt="Site Logo"/>
                    <h3>Sign In</h3>
                    <span>to watch our videos</span>
                    
                </div>

                <form method="POST">

                    <?php echo $account->getError(Constants::$loginFailed)?>
                    <input type="text" name="username" placeholder="Username" value="<?php getInputValue("username"); ?>" required>
                    
                   
                    <input type="password" name="password" placeholder="Password" required>
                   
                    <input type="submit" name="submitButton" value="Submit">
                </form>

                <a href="register.php" class="signInMessage">Don't have an account? Sign up here!</a>
            </div>

        </div>    

    </body>
</html>