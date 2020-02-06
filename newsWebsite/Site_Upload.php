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
    <h1>Story Upload</h1>
    <?php
    require 'Connection.php';
    session_start();
    $token=$_SESSION['token'];

    if (isset($_SESSION['user_id'])) {
        echo "<form method='POST' id='story'>
        <label>Title: <input type='text' name='title' /> </label>
        <br />
        <label> Text: <br /><textarea form='story' rows='6' cols='80' type='text' name='text'></textarea> </label>
        <input type='hidden' name='token' value='$token' />
        <br />
        <input type='submit' value='Upload' />
        </form>";
        if (isset($_POST['title']) && isset($_POST['text'])) {
            $title = $_POST['title'];
            $text = $_POST['text'];
            $user_id = $_SESSION['user_id'];
            $stmt = $mysqli->prepare("insert into stories (title, text, user_id) values (?,?,?)");
            if (!$stmt) {
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $safe_title = $mysqli->real_escape_string($title);
            $safe_text = $mysqli->real_escape_string($text);
            $safe_id = $mysqli->real_escape_string($user_id);

            $stmt->bind_param('ssi', $safe_title, $safe_text, $safe_id);
            $stmt->execute();
            $stmt->close();
            header('Location: http://ec2-52-15-237-123.us-east-2.compute.amazonaws.com/~clay/News/NewsSite.php');
        }
    } else {
        echo "Must be Logged-In in order to upload a story";
    }
    ?>
</body>

</html>