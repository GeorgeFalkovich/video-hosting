<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 24/04/2020
 * Time: 12:19
 */

class NavigationMenuProvider
{

    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {
        $menuHtml = $this->createNavItem("Home", "assets/img/icons/home.png", "index.php");
        $menuHtml .= $this->createNavItem("Trending", "assets/img/icons/trending.png", "trending.php");
        $menuHtml .= $this->createNavItem("Subscriptions", "assets/img/icons/subscriptions.png", "subscriptions.php");
        $menuHtml .= $this->createNavItem("Liked Videos", "assets/img/icons/thumb-up.png", "likedVideos.php");

        if(User::isLoggedIn()) {
            $menuHtml .= $this->createNavItem("Settings", "assets/img/icons/settings.png", "settings.php");
            $menuHtml .= $this->createNavItem("Log Out", "assets/img/icons/logout.png", "logout.php");

            $menuHtml .= $this->createSubscriptionsSection();
        }

        return "<div class='navigationItems'>
                    $menuHtml
                </div>";
    }

    private function createNavItem($text, $icon, $link) {
        return "<div class='navigationItem'>
                    <a href='$link'>
                        <img src='$icon'>
                        <span>$text</span>
                    </a>
                </div>";
    }

    private function createSubscriptionsSection() {
        $subscriptions = $this->userLoggedInObj->getSubscriptions();

        $html = "<span class='heading'>Subscriptions</span>";
        foreach($subscriptions as $sub) {
            $subUsername = $sub->getUsername();
            $html .= $this->createNavItem($subUsername, $sub->getProfilePic(), "profile.php?username=$subUsername");
        }
        return $html;
    }

}