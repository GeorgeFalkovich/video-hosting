<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 17/03/2020
 * Time: 8:57
 */

class VideoDataUpload
{

    public $videoDataArray, $title, $description, $privacy, $category, $uploadedBy;

    public function __construct($videoDataArray, $title, $description, $privacy, $category, $uploadedBy)
    {
        $this->videoDataArray = $videoDataArray;
        $this->title = $title;
        $this->description = $description;
        $this->privacy = $privacy;
        $this->category = $category;
        $this->uploadedBy = $uploadedBy;
    }


}