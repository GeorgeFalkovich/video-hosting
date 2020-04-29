<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 04/04/2020
 * Time: 16:06
 */

require_once ('../includes/config.php');
require_once ('../includes/classes/User.php');
require_once ('../includes/classes/Comment.php');



if(isset($_POST['commentText']) && isset($_POST['postedBy']) && isset($_POST['videoId'])) {
    $userLoggedInObj = new User($con, $_SESSION['userLoggedIn']);

    $postedBy = $_POST['postedBy'];
    $videoId = $_POST['videoId'];
    $responseTo = isset($_POST['responseTo']) ? $_POST['responseTo'] : 0;
    $commentText = $_POST['commentText'];


   $query = $con->prepare("INSERT INTO comments(postedBy, videoId, responseTo, body)
                                                  VALUES (:postedBy, :videoId, :responseTo, :body) ");

   $query->bindParam(":postedBy", $postedBy);
   $query->bindParam(":videoId", $videoId);
   $query->bindParam(":responseTo", $responseTo);
   $query->bindParam(":body", $commentText);

   $query->execute();

   // Return new comment HTML
    $newComment = new Comment($con, $con->lastInsertId(), $userLoggedInObj, $videoId);
    echo $newComment->create();
}
else {
    echo "FAILED";
}

