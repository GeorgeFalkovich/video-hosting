<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 24/04/2020
 * Time: 23:18
 */

require_once("includes/header.php");

if(!User::isLoggedIn()) {
    header("Location: signin.php");
}

$subscriptionsProvider = new SubscriptionsProvider($con, $userLoggedInObj);
$videos = $subscriptionsProvider->getVideos();

$videoGrid = new VideoGrid($con, $userLoggedInObj);
?>
<div class="largeVideoGridContainer">
    <?php
    if(sizeof($videos) > 0) {
        echo $videoGrid->createLarge($videos, "New from your subscriptions", false);
    }
    else {
        echo "No videos to show";
    }
    ?>
</div>