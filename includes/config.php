<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 15/03/2020
 * Time: 21:37
 */

ob_start();
session_start();

date_default_timezone_set("Europe/London");


try {
    $con = new PDO("mysql:dbname=VideoTube;host=localhost", "root", "");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(PDOException $e) {
   echo "Connection failed" . $e->getMessage();
}

?>