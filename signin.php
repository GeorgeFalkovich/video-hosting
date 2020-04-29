<?php
require_once("includes/config.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/FormSanitizer.php");


$account = new Account($con);

if(isset($_POST['submitButton'])) {

    $username = FormSanitizer::sanitizeFormUsername($_POST['username']);
    $password = FormSanitizer::sanitizeFormPassword($_POST['password']);

    $wasSuccessful = $account->login($username, $password);

    if($wasSuccessful) {
        $_SESSION['userLoggedIn'] = $username;
        header('Location: index.php');
    }
}


function getInputValue($name)
{
    if (isset($_POST[$name])) {
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
            <h3>Sign In</h3> <span>To continue to VideoTube</span>
        </div>
        <div class="loginForm">
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                <?php echo $account->getError(Constants::$noUser); ?>
                <input type="text" name="username" value="<?php getInputValue("username") ?>" placeholder="Username" required autocomplete="off">
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="SUBMIT" name="submitButton">
            </form>
        </div>

        <a href="signup.php" class="signInMessage">New? Sign up here!</a>
    </div>
</div>

</body>

</html>