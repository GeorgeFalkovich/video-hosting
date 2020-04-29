<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 07/04/2020
 * Time: 22:26
 */
require_once ("ButtonProvider.php");

class CommentControls
{

    private $con, $comment, $userLoggenInObj;

    public function __construct( $con, $comment, $userLoggenInObj)
    {
        $this->con = $con;
        $this->comment = $comment;
        $this->userLoggenInObj = $userLoggenInObj;


    }

    public function create() {
        $replyButton = $this->createReplyButton();
        $likesCount = $this->createLikesCount();
        $likeButton = $this->createLikeButton();
        $dislikeButton = $this->createDislikeButton();
        $replySection = $this->createReplySection();

        return "<div class='controls'>
                    $replyButton
                    $likesCount
                    $likeButton
                    $dislikeButton
                  </div>
                  $replySection";

    }

    private function createReplyButton() {
        $text = "REPLY";
        $action = "toggleReply(this)";
        return ButtonProvider::createButton($text, null, $action, null);
    }

    private function createLikesCount() {
        $text = $this->comment->getLikes();

        if($text == 0) {
            $text = "";
        }

        return "<span class='likesCount'>$text</span>";
    }

    private function createReplySection() {
        $postedBy = $this->userLoggenInObj->getUsername();
        $videoId = $this->comment->getVideoId();
        $commentId = $this->comment->getId();

        $profileButton = ButtonProvider::createUserProfileButton($this->con, $postedBy);

        $cancelButtonAction = "toggleReply(this)";
        $cancelButton = ButtonProvider::createButton("Cancel", null,  $cancelButtonAction, "cancelComment");

        $postButtonAction = "postComment(this, \" $postedBy \", $videoId, $commentId, \"repliesSection\")";
        $postButton = ButtonProvider::createButton("Reply", null,  $postButtonAction, "postComment");

        return "<div class='commentForm hidden'>
                                $profileButton
                                    <textarea placeholder='Add comment' cols='30' rows='10' class='commentBodyClass'></textarea>
                                    $cancelButton
                                        $postButton                                
                                    </div>";
    }

    private function createLikeButton() {
     //   $text = $this->comment->getLikes();
        $commentId = $this->comment->getId();
        $videoId = $this->comment->getVideoId();
        $action = "likeComment($commentId, this, $videoId)";
        $class = "likeButton";
        $imgSrc = "assets/img/icons/thumb-up.png";

        if($this->comment->wasLikedBy()) {
            $imgSrc = "assets/img/icons/thumb-up-active.png";
        }

        return ButtonProvider::createButton("", $imgSrc, $action, $class);
    }

    private function createDislikeButton() {
   //     $text = $this->video->getDislikes();
        $commentId = $this->comment->getId();
        $videoId = $this->comment->getId();
        $action = "dislikeComment($commentId, this, $videoId)";
        $class = "dislikeButton";

        $imgSrc = "assets/img/icons/thumb-down.png";

        if($this->comment->wasDislikedBy()) {
            $imgSrc = "assets/img/icons/thumb-down-active.png";
        }

        return ButtonProvider::createButton("", $imgSrc, $action, $class);
    }


}