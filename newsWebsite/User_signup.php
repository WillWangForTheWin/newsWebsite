<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>News</title>
    <link rel="stylesheet" type="text/css" href="./myWebPage.css" />
</head>

<body>
    <h1>New User Sign-Up</h1>
    <form method="POST">
        <label> Username: <input type="text" name="username" /></label>
        <label> Password: <input type="text" name="password" /></label>
        <input type="submit" value="Submit" />
    </form>
    <?php
    require 'Connection.php';
    session_start();
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("insert into UserInfo (username, hashed_password) values (?,?)");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $safe_username = $mysqli->real_escape_string($username);
        $safe_password = $mysqli->real_escape_string($password);

        $stmt->bind_param('ss', $safe_username, $safe_password);
        $stmt->execute();
        $stmt->close();
        header('Location: http://ec2-52-15-237-123.us-east-2.compute.amazonaws.com/~clay/News/NewsSite.php');
    }
    ?>
</body>

</html>