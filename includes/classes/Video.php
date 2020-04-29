<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 23/03/2020
 * Time: 14:10
 */

class Video
{

    private $con, $sqlData, $userLoggedInObj;

    public function __construct($con, $input, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;

        if(is_array($input)) {
            $this->sqlData = $input;
        }

        else {

            $query = $this->con->prepare("SELECT * FROM videos WHERE id = :id");
            $query->bindParam(':id', $input);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);

        }


    }
    public function getLikes() {
        $videoId = $this->getId();
        $query = $this->con->prepare("SELECT count(*) as 'count' FROM likes WHERE videoId = :videoId");
        $query->bindParam(":videoId", $videoId);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);

        return $data['count'];

    }

    public function getDislikes() {
        $videoId = $this->getId();
        $query = $this->con->prepare("SELECT count(*) as 'count' FROM dislikes WHERE videoId = :videoId");
        $query->bindParam(":videoId", $videoId);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);

        return $data['count'];

    }

    public function getId() {
        return $this->sqlData['id'];
    }

    public function getUploadedBy() {
        return $this->sqlData['uploadedBy'];
    }

    public function getTitle() {
        return $this->sqlData['title'];
    }

    public function getDescription() {
        return $this->sqlData['description'];
    }

    public function getPrivacy() {
        return $this->sqlData['privacy'];
    }

    public function getFilePath() {
        return $this->sqlData['filePath'];
    }

    public function getCategory() {
        return $this->sqlData['category'];
    }

    public function getViews() {
        return $this->sqlData['views'];
    }

    public function getDuration() {
        return $this->sqlData['duration'];
    }

    public function getUploadDate() {
        $date = $this->sqlData["uploadedDate"];
        return date("M j, Y", strtotime($date));
    }

    public function getTimeStamp() {
        $date = $this->sqlData["uploadedDate"];
        return date("M jS, Y", strtotime($date));
    }

    public function getNumbersOfComments() {
        $id = $this->getId();
        $query = $this->con->prepare("SELECT * FROM comments WHERE videoId=:videoId");
        $query->bindParam(":videoId", $id);
        $query->execute();
        return $query->rowCount();
    }

    public function incrementViews() {
        $videoId = $this->getId();
        $query = $this->con->prepare("UPDATE videos SET views = views+1 WHERE id = :id");
        $query->bindParam(":id", $videoId);
        $query->execute();

        $this->sqlData['views'] = $this->sqlData['views'] + 1;
    }

    public function like() {
        $id = $this->getId();
        $username = $this->userLoggedInObj->getUsername();

        if($this->wasLikedBy()) {
            // User already liked
            $query = $this->con->prepare("DELETE FROM likes WHERE username=:username  AND videoId=:videoId");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $id);
            $query->execute();

            $result = array(
                "likes" => -1,
                "dislikes" => 0
            );

            return json_encode($result);
        }

        else {
//            Delete from dislikes
            $query = $this->con->prepare("DELETE FROM dislikes WHERE username=:username  AND videoId=:videoId");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $id);
            $query->execute();
            $count = $query->rowCount();
            // User has not liked
            $query = $this->con->prepare("INSERT INTO likes(username, videoId) VALUES (:username, :videoId)");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $id);
            $query->execute();
        }

        $result = array(
            "likes" => 1,
            "dislikes" => 0 - $count
        );

        return json_encode($result);

    }

    public function disLike() {
        $id = $this->getId();
        $username = $this->userLoggedInObj->getUsername();

        if($this->wasDislikedBy()) {
            // User already liked
            $query = $this->con->prepare("DELETE FROM dislikes WHERE username=:username  AND videoId=:videoId");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $id);
            $query->execute();

            $result = array(
                "likes" => 0,
                "dislikes" => -1
            );

            return json_encode($result);
        }

        else {
//            Delete from  likes
            $query = $this->con->prepare("DELETE FROM likes WHERE username=:username  AND videoId=:videoId");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $id);
            $query->execute();
            $count = $query->rowCount();
            // User has not liked
            $query = $this->con->prepare("INSERT INTO dislikes(username, videoId) VALUES (:username, :videoId)");
            $query->bindParam(":username", $username);
            $query->bindParam(":videoId", $id);
            $query->execute();
        }

        $result = array(
            "likes" => 0 - $count,
            "dislikes" => 1
        );

        return json_encode($result);

    }


    public function wasLikedBy() {
        $id = $this->getId();
        $username = $this->userLoggedInObj->getUsername();
        $query = $this->con->prepare("SELECT * FROM likes WHERE username=:username AND videoId=:videoId");
        $query->bindParam(":username", $username);
        $query->bindParam(":videoId", $id);
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function wasDislikedBy() {
        $id = $this->getId();
        $username = $this->userLoggedInObj->getUsername();
        $query = $this->con->prepare("SELECT * FROM dislikes WHERE username=:username AND videoId=:videoId");
        $query->bindParam(":username", $username);
        $query->bindParam(":videoId", $id);
        $query->execute();

        return $query->rowCount() > 0;
    }

    public function getComments() {

        $id = $this->getId();
        $query = $this->con->prepare("SELECT * FROM comments WHERE videoId=:videoId ORDER BY datePosted DESC");
        $query->bindParam(":videoId", $id);
        $query->execute();

        $comments = array();
        while( $row = $query->fetch(PDO::FETCH_ASSOC)) {
            $comment = new Comment($this->con, $row, $this->userLoggedInObj, $id);
            array_push($comments, $comment);
        }

        return $comments;


    }

    public function  getThumbnail() {
        $videoId = $this->getId();
        $query = $this->con->prepare("SELECT filePath FROM thumbnails WHERE videoId=:videoId AND selected=1");
        $query->bindParam(":videoId", $videoId);
        $query->execute();

        return $query->fetchColumn();
    }


}