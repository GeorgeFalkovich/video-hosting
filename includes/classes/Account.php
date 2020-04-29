<?php
/**
 * Created by PhpStorm.
 * User: georgefalkovich
 * Date: 20/03/2020
 * Time: 16:35
 */


class Account
{
    private $con;
    private $errorArray = array();

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function login($un, $pw) {
        $pw = hash("sha512", $pw);

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");
        $query->bindParam(':un', $un);
        $query->bindParam(':pw', $pw);

        $query->execute();

        if($query->rowCount() == 1) {
            return true;
        }

        else {

            array_push($this->errorArray, Constants::$noUser);
            return false;
        }
    }

    public function register($fn, $ln, $un, $em, $em2, $pw, $pw2) {
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateUserName($un);
        $this->validateEmails($em, $em2);
        $this->validatePassword($pw, $pw2);

        if(empty($this->errorArray)) {
           return $this->insertUserDetails($fn, $ln, $un, $em, $pw);
        }

        else {
            echo "FALSE";
            return false;
        }

    }

    public function updateDetails($fn, $ln, $em, $un) {
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateNewEmail($em, $un);

        if(empty($this->errorArray)) {
            $query = $this->con->prepare("UPDATE users SET firstName=:fn, lastName=:ln, email=:em WHERE username=:un");
            $query->bindParam(":fn", $fn);
            $query->bindParam(":ln", $ln);
            $query->bindParam(":em", $em);
            $query->bindParam(":un", $un);

            return $query->execute();
        }
        else {
            return false;
        }
    }

    public function insertUserDetails($fn, $ln, $un, $em, $pw) {
         $pw = hash("sha512",  $pw);
         $profilePic = "assets/img/profilePictures/default.png";

         $query = $this->con->prepare("INSERT INTO users(firstName, lastName, username, email, password, profilePic) VALUES(:fn, :ln, :un, :em, :pw, :pic )");
         $query->bindParam(":fn", $fn);
         $query->bindParam(":ln", $ln);
         $query->bindParam(":un", $un);
         $query->bindParam(":em", $em);
         $query->bindParam(":pw", $pw);
         $query->bindParam(":pic", $profilePic);



        return $query->execute();
    }

    private function validateFirstName($fn) {
        if(strlen($fn) > 25 || strlen($fn) < 2) {
            array_push($this->errorArray, Constants::$firstNameCharacters);
        }
    }

    private function validateLastName($ln) {
        if(strlen($ln) > 25 || strlen($ln) < 2) {
            array_push($this->errorArray, Constants::$lastNameCharacters);
        }
    }

    private function validateUserName($un) {
        if(strlen($un) > 25 || strlen($un) < 5) {
            array_push($this->errorArray, Constants::$userNameCharacters);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE username = :un");
        $query->bindParam(":un", $un);
        $query->execute();

        if($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$usernameTaken);
        }
    }

    private function validatePasswords($pw, $pw2) {
        if($pw != $pw2) {
            array_push($this->errorArray, Constants::$passwordsDoNotMatch);
            return;
        }

        if(preg_match("/[^A-Za-z0-9]/", $pw)) {
            array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
            return;
        }

        if(strlen($pw) > 30 || strlen($pw) < 5) {
            array_push($this->errorArray, Constants::$passwordLength);
        }
    }

    private function validateEmails($em, $em2) {
        if($em  != $em2) {
            array_push($this->errorArray, Constants::$emailDoNotMutch);
            return;
        }

        if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE email = :em");
        $query->bindParam(":em", $em);
        $query->execute();

        if($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$emailTaken);
        }
    }

    private function validateNewEmail($em, $un) {

        if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        $query = $this->con->prepare("SELECT email FROM users WHERE email=:em AND username != :un");
        $query->bindParam(":em", $em);
        $query->bindParam(":un", $un);
        $query->execute();

        if($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$emailTaken);
        }

    }

    private function validatePassword($pwd, $epwd2) {
        if($pwd  != $epwd2) {
            array_push($this->errorArray, Constants::$passwordDoNotMatch);
            return;
        }

        if(preg_match("/[^A-Za-z0-9]/", $pwd)) {
            array_push($this->errorArray, Constants::$passwordInvalid);
            return;
        }

        if(strlen($pwd) > 30 || strlen($pwd) < 5) {
            array_push($this->errorArray, Constants::$pwdLength);
            return;
        }


    }

    public function getError($error) {
        if(in_array($error, $this->errorArray)) {
            return "<p class='errorMessage'>$error</p>";
        }
    }

    public function updatePassword($oldPw, $pw, $pw2, $un) {
        $this->validateOldPassword($oldPw, $un);
        $this->validatePasswords($pw, $pw2);

        if(empty($this->errorArray)) {
            $query = $this->con->prepare("UPDATE users SET password=:pw WHERE username=:un");
            $pw = hash("sha512", $pw);
            $query->bindParam(":pw", $pw);
            $query->bindParam(":un", $un);

            return $query->execute();
        }
        else {
            return false;
        }
    }



    private function validateOldPassword($oldPw, $un) {
        $pw = hash("sha512", $oldPw);

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");
        $query->bindParam(":un", $un);
        $query->bindParam(":pw", $pw);

        $query->execute();

        if($query->rowCount() == 0) {
            array_push($this->errorArray, Constants::$passwordIncorrect);
        }
    }

    public function getFirstError() {
        if(!empty($this->errorArray)) {
            return $this->errorArray[0];
        }
        else {
            return "";
        }
    }



}