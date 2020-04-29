<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 17/03/2020
 * Time: 9:44
 */

class VideoProcessor
{

    private $con;
    private $sizeLimit = 10000000;
    private $allowedTypes = array("mp4", "flv", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg", "pdf");
    private $ffmpegPath = "ffmpeg/bin/ffmpeg";
    private $ffprobePath = "ffmpeg/bin/ffprobe";

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function upload($videoUploadData) {

        $targetDir = "uploads/videos/";
        $videoData = $videoUploadData->videoDataArray;
        $tempFilePath = $targetDir . uniqid() . basename($videoData['name']);
        $tempFilePath = str_replace(" ", "_", $tempFilePath);
        $isValidData = $this->processData($videoData, $tempFilePath);

        if(!$isValidData) {
            echo "False valid data";
            return false;
        }
        if(move_uploaded_file($videoData['tmp_name'], $tempFilePath)) {
            $finalPathFile = $targetDir .uniqid(). ".mp4";

                    if(!$this->insertVideoData($videoUploadData, $finalPathFile)) {
                        echo "Insert query failed";
                        return false;
                    }

                    if(!$this->convertVideoToMp4($tempFilePath, $finalPathFile)) {
                        echo "Upload failed!";
                        return false;
                    }

                    if(!$this->deleteFile($tempFilePath)) {
                        echo "Upload failed!";
                        return false;
                    }

                    if(!$this->generateThumbnails($finalPathFile)) {
                        echo "Uploaded failed - Could not generate thumbnails!";
                        return false;
                    }

                    return true;

        }

    }

    private function processData($videoData, $filePath) {
        $videoType = pathinfo($filePath, PATHINFO_EXTENSION);

        if(!$this->isValidSize($videoData)) {
            echo "File to large. Can't be more than " . $this->sizeLimit . " bytes";
            return false;
        }

        else if (!$this->isValidType($videoType)) {
             echo "Invalid file type";
             return false;
        }

        else if ($this->hasError($videoData)) {
            echo "Error code: ". $videoData['error'];
            return false;
        }

        else {
            return true;
        }

    }

    private function isValidSize($data) {
        return $data['size'] <= $this->sizeLimit;
    }

    private function isValidType($type) {
        $lowercased = strtolower($type);
        return in_array($lowercased, $this->allowedTypes);
    }

    private function hasError($data) {
        return $data["error"] != 0;
    }

    private function insertVideoData($uploadData, $filePath) {
        $query = $this->con->prepare("INSERT INTO videos(title, uploadedBy, description, privacy, category, filePath) 
                                            VALUES(:title, :uploadedBy, :description, :privacy, :category, :filePath)");
        $query->bindParam(":title", $uploadData->title);
        $query->bindParam(":uploadedBy", $uploadData->uploadedBy);
        $query->bindParam(":description", $uploadData->description);
        $query->bindParam(":privacy", $uploadData->privacy);
        $query->bindParam(":category", $uploadData->category);
        $query->bindParam(":filePath", $filePath);

        return $query->execute();
    }

    public function convertVideoToMp4($tempFilePath, $finalPathFile) {
        $cmd = "$this->ffmpegPath -i $tempFilePath $finalPathFile 2>&1";

        $outputLog = array();
        exec($cmd, $outputLog, $returnCode);

        if($returnCode != 0) {
//            Command failed
            foreach ($outputLog as $line) {
                echo $line."<br>";
            }

            return false;
        }

        return true;
    }

    private function deleteFile($filePath) {
        if(!unlink($filePath)) {
            echo "Could not delete file!\n";
            return false;
        }

        return true;
    }

    public function generateThumbnails($filePath) {

        $thumbnailSize = "210x118";
        $numThumbnails = 3;
        $pathToThumbnails = "uploads/videos/thumbnails";

        $duration = $this->getVideoDuration($filePath);
        $videoId = $this->con->lastInsertId();
        $this->updateDuration($duration, $videoId);

        for($num = 1; $num <= $numThumbnails; $num++) {
            $imageName = uniqid() . ".jpg";
            $interval = ($duration * 0.8) / $numThumbnails * $num;
            $fullThumbNailPath = "$pathToThumbnails/$videoId-$imageName";

            $cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbNailPath 2>&1";

            $outputLog = array();
            exec($cmd, $outputLog, $returnCode);

            if($returnCode != 0) {
//            Command failed
                foreach ($outputLog as $line) {
                    echo $line."<br>";
                }

            }

            $selected = $num == 1 ? 1 : 0;
            $query = $this->con->prepare("INSERT INTO thumbnails(videoid, filePath, selected) VALUES(:videoid, :filePath, :selected)");
            $query->bindParam(":videoid", $videoId);
            $query->bindParam(":filePath", $fullThumbNailPath);
            $query->bindParam(":selected", $selected);

            $success = $query->execute();

            if (!$success) {
                echo "Error inserting thumbnails";
                return false;
            }


        }

        return true;
    }

    private function getVideoDuration($filePath) {
        return (int)shell_exec("$this->ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
    }

    private function updateDuration($duration, $videoId) {
        $hours = floor($duration / 3600);
        $mins = floor(($duration - ($hours * 3600)) / 60);
        $secs = floor($duration % 60);

        $hours = ($hours < 1) ? "" : $hours . ":";
        $mins = ($mins < 10) ? "0". $mins . ":" : $mins . ":";
        $secs = ($secs < 10) ? "0". $secs : $secs;

        $duration = $hours.$mins.$secs;

        $query = $this->con->prepare("UPDATE videos SET duration = :duration WHERE id = :videoId");
        $query->bindParam(":duration", $duration);
        $query->bindParam(":videoId", $videoId);
        $query->execute();



    }

}