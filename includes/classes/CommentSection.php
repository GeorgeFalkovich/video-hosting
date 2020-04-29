<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 05/04/2020
 * Time: 21:28
 */

class CommentSection
{

    private $con, $video, $userLoggenInObj;

    public function __construct($con, $video, $userLoggenInObj)
    {
        $this->video = $video;
        $this->con = $con;
        $this->userLoggenInObj = $userLoggenInObj;


    }

    public function create() {
      return  $this->createCommentSection();
    }

    private function createCommentSection() {
        $numComments = $this->video->getNumbersOfComments();
        $postedBy = $this->userLoggenInObj->getUsername();
        $videoId = $this->video->getId();

        $profileButton = ButtonProvider::createUserProfileButton($this->con, $postedBy);
        $commentAction = "postComment(this, \"$postedBy\", $videoId, 12, \"comments\")";
        $commentButton = ButtonProvider::createButton("COMMENT", null,  $commentAction, "postComment");

        $comments = $this->video->getComments();
        $commentItems = "";
        foreach($comments as $comment) {
            $commentItems .= $comment->create();
        }


        //Get comments HTML

        return "<div class='commentSection'>
                    <div class='header'>
                        <span class='commentCount'>$numComments Comments</span>
                            <div class='commentForm'>
                                $profileButton
                                    <textarea placeholder='Add comment' cols='30' rows='10' class='commentBodyClass'></textarea>
                                        $commentButton
                                    </div>
                                </div>
                        
                        <div class='comments'>
                              $commentItems
                            </div>
                        </div>";
    }



}