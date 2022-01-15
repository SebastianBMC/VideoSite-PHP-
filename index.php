<?php
 require_once("includes/Header.php");

    $preview = new PreviewProvider($connection, $userLoggedIn);
    echo $preview->createPreviewVideo(null);

    $containers = new CategoryContainer($connection, $userLoggedIn);
    echo $containers->showAllCategories();


?>