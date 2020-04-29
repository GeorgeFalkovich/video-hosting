<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 24/03/2020
 * Time: 22:10
 */

class ButtonProvider
{
    public static $signInFunction = "notSignedIn()";

    public static function createLink($link) {
        return User::isLoggedIn() ? $link : ButtonProvider::$signInFunction;
    }

    public static function createButton($text, $imageSrc, $action, $class) {
        $image  = ($imageSrc == null) ? "" : "<img src='$imageSrc'>";

//        Change action if need
        $action = ButtonProvider::createLink($action);

        return "<button class='$class' onclick='$action'>$image<span class='text'>$text</span></button>";
    }


    public static function createHyperlinkButton($text, $imageSrc, $href, $class) {
        $image  = ($imageSrc == null) ? "" : "<img src='$imageSrc'>";

        return " <a href='$href'><button class='$class'>$image<span class='text'>$text</span></button></a> ";
    }

    public static function createUserProfileButton($con, $username) {
        $userObj = new User($con, $username);
        $profilePic = $userObj->getProfilePic();
        $link = "profile.php?username=$username";

        return "<a href='$link'> <img src='$profilePic' alt='' class='profilePic'></a>";

    }

    public static function createEditVideoButton($videoId) {
        $href = "editVideo.php?videoId=$videoId";
        $button = ButtonProvider::createHyperlinkButton("EDIT VIDEO", null, $href, "edit button");

        return "<div class='editVideoButtonContainer'>$button</div>";
}

    public static function createSubscriberButton($con, $userToObj, $userLoggedInObj) {
        $userTo = $userToObj->getUsername();
        $userLoggedIn = $userLoggedInObj->getUsername();

        $isSubscribed = $userLoggedInObj->isSubscribed($userTo);
        $buttonText = $isSubscribed ? "SUBSCRIBED" : "SUBSCRIBE";
        $buttonText .= " " . $userToObj->getSubscruberCount();

        $buttonClass = $isSubscribed ? "unsubscribe button" : "subscribe button";
        $action = "subscribe(\"$userTo\", \"$userLoggedIn\", this)";

        $button = ButtonProvider::createButton($buttonText, null, $action, $buttonClass);

        return "<div class='subscribeButtonContainer'>$button</div>";
    }

    public static function createUserProfileNavigationButton($con, $username) {
        if(User::isLoggedIn()) {
            return ButtonProvider::createUserProfileButton($con, $username);
        }
        else {
            return "<a href='signin.php'>
                        <span class='signInLink'>SIGN IN</span>
                    </a>";
        }
    }

}