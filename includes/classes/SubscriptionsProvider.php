<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 23/04/2020
 * Time: 22:55
 */

class SubscriptionsProvider
{

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function getVideos() {
        $videos = array();
        $subscriptions = $this->userLoggedInObj->getSubscriptions();
        if(sizeof($subscriptions) > 0) {

            // user1, user2, user3
            // SELECT * FROM videos WHERE uploadedBy = ? OR uploadedBy = ? OR uploadedBy = ?
            // $query->bindParam(1, "user1");
            // $query->bindParam(2, "user2");
            // $query->bindParam(3, "user3");

            $condition = "";
            $i = 0;

            while($i < sizeof($subscriptions)) {

                if($i == 0) {
                    $condition .= "WHERE uploadedBy=?";
                }
                else {
                    $condition .= " OR uploadedBy=?";
                }
                $i++;
            }

            $videoSql = "SELECT * FROM videos $condition ORDER BY uploadedDate DESC";
            $videoQuery = $this->con->prepare($videoSql);
            $i = 1;

            foreach($subscriptions as $sub) {
                $subUsername = $sub->getUsername();
                $videoQuery->bindValue($i, $subUsername);
                $i++;
            }

            $videoQuery->execute();
            while($row = $videoQuery->fetch(PDO::FETCH_ASSOC)) {
                $video = new Video($this->con, $row, $this->userLoggedInObj);
                array_push($videos, $video);
            }

        }

        return $videos;

    }


}

?>