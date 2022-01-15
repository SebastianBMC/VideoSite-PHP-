<?php
    require_once("includes/config.php");
    require_once("includes/classes/PreviewProvider.php");
    require_once("includes/classes/Entity.php");
    require_once("includes/classes/CategoryContainers.php");
    require_once("includes/classes/EntityProvider.php");
    require_once("includes/classes/Video.php");
    require_once("includes/classes/User.php");
    require_once("includes/classes/ErrorMessage.php");

    if(!isset($_SESSION["userLoggedIn"]))
    {
        header("Location: register.php");
    }

    $userLoggedIn = $_SESSION["userLoggedIn"];

?>


<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="assets/style/style.css"/>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/eb138ca255.js" crossorigin="anonymous"></script>
        <script src="assets/js/script.js"></script>
    </head>
    <body>
        <div class="wrapper">

<?php 
if(!isset($hideNav))
{
    include_once("includes/navbar.php");
}


?>
       

