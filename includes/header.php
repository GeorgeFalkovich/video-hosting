<?php
error_reporting(E_ERROR | E_PARSE);
require_once("includes/config.php");
require_once("includes/classes/ButtonProvider.php");
require_once("includes/classes/User.php");
require_once ('includes/classes/Video.php');
require_once ('includes/classes/VideoGrid.php');
require_once ('includes/classes/VideoGridItem.php');
require_once ('includes/classes/SubscriptionsProvider.php');
require_once ('includes/classes/NavigationMenuProvider.php');


$usernameLoggedIn =  User::isLoggedIn() ? $_SESSION['userLoggedIn'] : "";
$userLoggedInObj = new User($con, $usernameLoggedIn);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VideoHosting by George Falkovich</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/userActions.js"></script>

</head>
<body>

<div id="pageContainer">
    <div id="mastHeadContainer" class="navShowHide"><button><img src="assets/img/icons/menu.png" alt=""></button>
        <a href="index.php" class="logoContainer">
            <img src="assets/img/icons/VideoTubeLogo.png" alt="Site logo">
        </a>

        <div class="searchBarContainer">
            <form action="search.php" method="GET">
                <input type="text" class="searchBar" placeholder="Search..." name="term">
                <button class="searchButton"><img src="assets/img/icons/search.png" alt="Search"></button>
            </form>
        </div>

        <div class="rightIcons">
            <a href="upload.php">
                <img src="assets/img/icons/upload.png" alt="" class="upload">
            </a>
            <?php
                echo ButtonProvider::createUserProfileNavigationButton($con, $userLoggedInObj->getUsername());
             ?>

        </div>
    </div>
    <div id="sideNavContainer" style="display: none;">
        <?php
        $navigationProvider = new NavigationMenuProvider($con, $userLoggedInObj);
        echo $navigationProvider->create();
        ?>
    </div>
    <div id="mainSectionContainer">
        <div id="mainContentConteiner">