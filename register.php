<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Constants.php");

    $account = new Account($connection);

    
    if(isset($_POST["submitButton"]))
    {
        $firstName =  FormSanitizer::sanitizeFormString($_POST["firstName"]);
        $lastName =  FormSanitizer::sanitizeFormString($_POST["lastName"]);
        $password =  FormSanitizer::sanitizeFormPassword($_POST["password"]);
        $password2 =  FormSanitizer::sanitizeFormPassword($_POST["password2"]);
        $email =  FormSanitizer::sanitizeFormEmail($_POST["email"]);
        $email2 =  FormSanitizer::sanitizeFormEmail($_POST["email2"]);
        $userName =  FormSanitizer::sanitizeFormUsername($_POST["username"]);


        $success = $account->register($firstName, $lastName, $userName, $email, $email2, $password, $password2);

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
                    <h3>Sign Up</h3>
                    <span>to watch our videos</span>
                    
                </div>

                <form method="POST">

                    <?php echo $account->getError(Constants::$fistNameCharacters) ?>
                    <input type="text" name="firstName" placeholder="First Name"  value="<?php getInputValue("firstName"); ?>" required>
                    
                    <?php echo $account->getError(Constants::$lastNameCharacters)?>
                    <input type="text" name="lastName" placeholder="Last Name"  value="<?php getInputValue("lastName"); ?>" required>

                    <?php echo $account->getError(Constants::$usernameCharacters)?>
                    <?php echo $account->getError(Constants::$usernameTaken)?>
                    <input type="text" name="username" placeholder="Username"  value="<?php getInputValue("username"); ?>" required>
                    
                    <?php echo $account->getError(Constants::$emailsDontMatch)?>
                    <?php echo $account->getError(Constants::$emailInvalid)?>
                    <input type="email" name="email" placeholder="Email"   value="<?php getInputValue("email"); ?>"required>
                    <input type="text" name="email2" placeholder="Confirm Email" required>
                    
                    <?php echo $account->getError(Constants::$passwordsDontMatch)?>
                    <?php echo $account->getError(Constants::$passwordLength)?>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="password2" placeholder="Confirm Password" required>
                    
                    <input type="submit" name="submitButton" value="Submit">
                </form>
                <a href="login.php" class="signInMessage">Already have an account? Sign in here!</a>
            </div>

        </div>    

    </body>
</html>