<?php 
require_once("includes/Header.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/paypalConfig.php");
require_once("includes/classes/BillingDetails.php");

$user = new User($connection, $userLoggedIn);
$detailsMessage = "";
$passwordMessage = "";
$subscriptionMessage = "";


if(isset($_POST["saveDetailsButton"]))
{
    $account = new Account($connection);

    $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
    $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
    $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);

    if($account->updateDetails($firstName, $lastName, $email, $userLoggedIn))
    {
      $detailsMessage = "<div class='alertSuccess'>
                            Details updated successfully!
                        </div>";
    }else
    {
        $errorMessage = $account->getFirstError();
        $detailsMessage = "<div class='alertError'>
                                $errorMessage
                            </div>";
    }

}

if(isset($_POST["savePasswordButton"]))
{
    $account = new Account($connection);

    $oldPassword = FormSanitizer::sanitizeFormPassword($_POST["oldPassword"]);
    $newPassword = FormSanitizer::sanitizeFormPassword($_POST["newPassword"]);
    $newPassword2 = FormSanitizer::sanitizeFormPassword($_POST["newPassword2"]);

    if($account->updatePassword($oldPassword, $newPassword, $newPassword2, $userLoggedIn))
    {
      $passwordMessage = "<div class='alertSuccess'>
                                Password updated successfully!
                        </div>";
    }else
    {
        $errorMessage = $account->getFirstError();
        $passwordMessage = "<div class='alertError'>
                                $errorMessage
                            </div>";
    }

}
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $token = $_GET['token'];
    $agreement = new \PayPal\Api\Agreement();

    $subscriptionMessage = "<div class='alertError'>
                                Something went wrong!
                            </div>";
  
    try {
      // Execute agreement
      $agreement->execute($token, $apiContext);

      $result = BillingDetails::insertDetails($connection, $agreement, $token, $userLoggedIn);
      $result = $result && $user->setIsSubscribed(1);

      if($result)
      {
        $subscriptionMessage = "<div class='alertSuccess'>
                                     You're all set!
                                </div>";
      }
      // Update user's account status

    } catch (PayPal\Exception\PayPalConnectionException $ex) {
      echo $ex->getCode();
      echo $ex->getData();
      die($ex);
    } catch (Exception $ex) {
      die($ex);
    }
  } 
  else if (isset($_GET['success']) && $_GET['success'] == 'false') {
    $subscriptionMessage = "<div class='alertSuccess'>
                            User canceled or something went wrong!
                        </div>";
  }



?>


<style>
    .navLinks a
    {
        color: #141414;
    }

    .rightItems a i
    {
        color: #141414;
    }
    .formSection h3
    {
        color: rgb(48, 48, 177);
    }



</style>



<div class="settingsContainer column">
    <div class="formSection">
        <form method="POST">
            <h2>User Details</h2>
            
            <?php 
                $user = new User($connection, $userLoggedIn);

                $firstName = isset($_POST["firstName"]) ? $_POST["firstName"] :$user->getFirstName();
                $lastName = isset($_POST["lastName"]) ? $_POST["lastName"] :$user->getLastName();
                $email = isset($_POST["email"]) ? $_POST["email"] :$user->getEmail();
            ?>

            <input type="text" name="firstName" placeholder="First name" value="<?php echo $firstName?>">
            <input type="text" name="lastName" placeholder="First name" value="<?php echo $lastName?>">
            <input type="email" name="email" placeholder="Email" value="<?php echo $email?>">
            <div class="message">
                <?php echo $detailsMessage ?>
            </div>
            <input type="submit" name="saveDetailsButton" value="Save">
        </form>

    </div>
    <div class="formSection">
        <form method="POST">
            <h2>Update Password</h2>

            <input type="password" name="oldPassword" placeholder="Old password">
            <input type="password" name="newPassword" placeholder="New password">
            <input type="password" name="newPassword2" placeholder="Confirm new password">
            <div class="message">
                <?php echo $passwordMessage ?>
            </div>
            <input type="submit" name="savePasswordButton" value="Save">
        </form>

    </div>

    <div class="formSection">
        <h2>Subscription</h2>
        <div class="message">
                <?php echo $subscriptionMessage ?>
            </div>

        <?php 
            if($user->getIsSubscribed())
            {
                echo "<h3>You are subscribed! Go to PayPal to cancel.</h3>";

            }else
            {
                echo "<a href='billing.php'>Subscribe to MercaVids</a>";
            }
        
        ?>
    </div>
</div>