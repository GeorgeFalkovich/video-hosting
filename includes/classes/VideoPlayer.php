<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 23/03/2020
 * Time: 14:31
 */

class VideoPlayer
{

    private $video;

    public function __construct($video)
    {
        $this->video = $video;


    }

    public function create($autoPlay) {

        if($autoPlay) {
            $autoPlay = "autoplay";
        }

        else {
            $autoPlay = "";
        }

        $filePath = $this->video->getFilePath();

        return "<video class='videoPlayer' controls $autoPlay> <source src='$filePath' type='video/mp4'>
                    Your browser does not support video tag!
                         </video>";
    }

}