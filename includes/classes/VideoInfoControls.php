<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 24/03/2020
 * Time: 12:10
 */

require_once ("includes/classes/ButtonProvider.php");

class VideoInfoControls
{

    private $video, $userLoggenInObj;

    public function __construct( $video, $userLoggenInObj)
    {
        $this->video = $video;
        $this->userLoggenInObj = $userLoggenInObj;


    }

    public function create() {
        $likeButton = $this->createLikeButton();
        $dislikeButton = $this->createDislikeButton();

        return "<div class='controls'>
                    $likeButton
                        $dislikeButton
                    </div>";

    }

    private function createLikeButton() {
        $text = $this->video->getLikes();
        $videoId = $this->video->getId();
        $action = "likeVideo(this, $videoId)";
        $class = "likeButton";
        $imgSrc = "assets/img/icons/thumb-up.png";

        if($this->video->wasLikedBy()) {
            $imgSrc = "assets/img/icons/thumb-up-active.png";
        }

        return ButtonProvider::createButton($text, $imgSrc, $action, $class);
    }

    private function createDislikeButton() {
        $text = $this->video->getDislikes();
        $videoId = $this->video->getId();
        $action = "dislikeVideo(this, $videoId)";
        $class = "dislikeButton";

        $imgSrc = "assets/img/icons/thumb-down.png";

        if($this->video->wasDislikedBy()) {
            $imgSrc = "assets/img/icons/thumb-down-active.png";
        }

        return ButtonProvider::createButton($text, $imgSrc, $action, $class);
    }

}