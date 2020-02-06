<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="./myWebPage.css" />
</head>

<body>
    <h1>Login</h1>
    <form method="POST">
        <label> Username: <input type="text" name="username" /></label>
        <label> Password: <input type="password" name="password" /></label>
        <input type="submit" value="Login" />
    </form>
    <?php

    require 'Connection.php';
    session_start();
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $stmt = $mysqli->prepare("SELECT COUNT(*), id, hashed_password FROM UserInfo WHERE username=?");

        // Bind the parameter
        $safe_user = $mysqli->real_escape_string($user);
        $stmt->bind_param('s', $safe_user);
        $safe_user = $_POST['username'];
        $stmt->execute();

        // Bind the results
        $stmt->bind_result($cnt, $user_id, $pwd_hash);
        $stmt->fetch();

        $pwd_guess = $_POST['password'];
        // Compare the submitted password to the actual password hash

        if ($cnt == 1 && password_verify($pwd_guess, $pwd_hash)) {
            // Login succeeded!
            $_SESSION['user_id'] = $user_id;
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
            // Redirect to your target page
            header('Location: http://ec2-52-15-237-123.us-east-2.compute.amazonaws.com/~clay/News/NewsSite.php');
        } else {
            // Login failed; redirect back to the login screen
            header('Location: http://ec2-52-15-237-123.us-east-2.compute.amazonaws.com/~clay/News/login.php');
        }
    }
    ?>
</body>

</html>