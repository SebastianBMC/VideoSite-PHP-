<?php 

$hideNav = true;
require_once("includes/header.php");


$video = new Video($connection, $_GET["id"]);

$video->incrementViews();

$user = new User($connection, $userLoggedIn);
if(!$user->getIsSubscribed())
{
    ErrorMessage::show("You must be subscribed to see this.
                        <a href='profile.php'>Click here to get started</a>");
}


?>

<div class="watchContainer">

    <div class="videoControls watchNav">
        <button onclick="goBack()"><i class="fas fa-arrow-left"></i></button>
        <h1><?php echo $video->getTitle(); ?></h1>
    </div>

    <video controls autoplay>
        <source src='<?php echo $video->getFilePath(); ?>' type="video/mp4">
    </video>

</div>
<script>
    initVideo("<?php echo $video->getId(); ?>", "<?php echo $userLoggedIn ?>");
</script>