<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Constants.php");

$account = new Account($con);



if(isset($_POST['submitButton'])) {
    $firstName = FormSanitizer::sanitizeFormString($_POST['firstName']);
    $lastName = FormSanitizer::sanitizeFormString($_POST['lastName']);

    $userName = FormSanitizer::sanitizeFormUsername($_POST['userName']);

    $email = FormSanitizer::sanitizeFormEmail($_POST['email']);
    $email2 = FormSanitizer::sanitizeFormEmail($_POST['email2']);

    $password = FormSanitizer::sanitizeFormPassword($_POST['password']);
    $password2 = FormSanitizer::sanitizeFormPassword($_POST['password2']);

    $wasSuccessful = $account->register($firstName, $lastName, $userName, $email, $email2, $password, $password2);

    if($wasSuccessful) {
        $_SESSION['userLoggedIn'] = $userName;
        header('Location: index.php');
    }
}

function getInputValue($name) {
    if(isset($_POST[$name])) {
        echo $_POST[$name];
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VideoTube</title>
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

</head>
<body>

<div class="signInContainer">
    <div class="column">
        <div class="header">
            <img src="assets/img/icons/VideoTubeLogo.png" alt="Site logo">
            <h3>Sign Up</h3> <span>To continue to VideoTube</span>
        </div>
        <div class="loginForm">
            <form action="signup.php" method="POST">
                <?php echo $account->getError(Constants::$firstNameCharacters); ?>
                <input type="text" name="firstName" placeholder="First name" value="<?php getInputValue('firstName'); ?>" autocomplete="off" required>

                <?php echo $account->getError(Constants::$lastNameCharacters); ?>
                <input type="text" name="lastName" placeholder="Last name" autocomplete="off" value="<?php getInputValue('lastName'); ?>" required>

                <?php echo $account->getError(Constants::$userNameCharacters); ?>
                <?php echo $account->getError(Constants::$usernameTaken); ?>
                <input type="text" name="userName" placeholder="User name" value="<?php getInputValue('userName'); ?>" autocomplete="off" required>

                <?php echo $account->getError(Constants::$emailDoNotMutch); ?>
                <?php echo $account->getError(Constants::$emailInvalid); ?>
                <?php echo $account->getError(Constants::$emailTaken); ?>
                <input type="email" name="email" placeholder="E-mail" value="<?php getInputValue('email'); ?>" autocomplete="off" required>
                <?php echo $account->getError(Constants::$emailDoNotMutch); ?>
                <input type="email" name="email2" placeholder="Confirm e-mail" value="<?php getInputValue('email2'); ?>" autocomplete="off" required>

                <?php echo $account->getError(Constants::$passwordDoNotMatch); ?>
                <?php echo $account->getError(Constants::$passwordInvalid); ?>
                <?php echo $account->getError(Constants::$pwdLength); ?>
                <input type="password" name="password" placeholder="Password" autocomplete="off" required>
                <input type="password" name="password2" placeholder="Confirm password" autocomplete="off" required>
                
                <input type="submit" name="submitButton" value="SUBMIT">
            </form>
        </div>

        <a href="signin.php" class="signInMessage">Already have account? Sign in here!</a>
    </div>
</div>

</body>

</html>