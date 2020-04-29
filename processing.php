<?php


/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 17/03/2020
 * Time: 8:49
 */


require ('includes/header.php');
require_once ("includes/classes/VideoDataUpload.php");
require_once ("includes/classes/VideoProcessor.php");

if(!isset($_POST['uploadButton'])) {
    echo "No file sent to page";
    exit();
}


// 1) Create file upload data

    $videoUploadData = new VideoDataUpload(
        $_FILES['fileInput'],
        $_POST['titleInput'],
        $_POST['descriptionInput'],
        $_POST['privaceInput'],
        $_POST['categoryInput'],
        $userLoggedInObj->getUsername()
        );

// 2) Process video data (upload)

$videoProcessor = new VideoProcessor($con);
$wasSuccessful = $videoProcessor->upload($videoUploadData);

// 3) Check if upload was successful

if($wasSuccessful) {
    echo "Upload successful!";
}



?>