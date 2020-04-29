<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 29/03/2020
 * Time: 16:34
 */

require_once ("../includes/config.php");
require_once ("../includes/classes/Video.php");
require_once ("../includes/classes/User.php");

$username = $_SESSION['userLoggedIn'];
$videoId = $_POST['videoId'];

$userLoggedInObj = new User($con, $username);
$video = new Video($con, $videoId, $userLoggedInObj);

echo  $video->like();

?>