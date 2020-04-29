<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 24/04/2020
 * Time: 23:09
 */

require_once("includes/header.php");
require_once("includes/classes/TrendingProvider.php");

$trendingProvider = new TrendingProvider($con, $userLoggedInObj);
$videos = $trendingProvider->getVideos();

$videoGrid = new VideoGrid($con, $userLoggedInObj);
?>
<div class="largeVideoGridContainer">
    <?php
    if(sizeof($videos) > 0) {
        echo $videoGrid->createLarge($videos, "Trending videos uploaded in the last week", false);
    }
    else {
        echo "No trending videos to show";
    }
    ?>
</div>