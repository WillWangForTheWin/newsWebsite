<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>News</title>
    <link rel="stylesheet" type="text/css" href="myWebPage.css" />
</head>

<body>
    <h1>The News Site to End All News</h1>
    <div class='button big-btn'>
        <a href="login.php">Login</a>
    </div>

    <div class='button big-btn'>
        <a href="User_signup.php">Sign-Up</a>
    </div>

    <div class='button big-btn'>
        <a href="Site_Upload.php">Upload Story</a>
    </div>

    <div class='button big-btn'>
        <form method='POST'>
            <input type='hidden' name='logout' value='logout' />
            <input type='submit' class='stories' value='Logout' />
        </form>
    </div>

    <h2> Stories </h2>

    <h3>sort by:</h3>
    <form action='sort.php' method="POST">
        <input type='hidden' name='sort' value='rating' />
        <input type='submit' value='Rating' />
    </form>

    <form action='sort.php' method="POST">
        <input type='hidden' name='sort' value='id' />
        <input type='submit' value='Newest' />
    </form>
    <?php
    require 'Connection.php';
    session_start();
    /* Story titles */
    $token=$_SESSION['token'];
    if ($_SESSION['sort'] == 'rating') {
        $stmt = $mysqli->prepare("select id, title, text, rating, user_id from stories order by rating desc");
    } else {
        $stmt = $mysqli->prepare("select id, title, text, rating, user_id from stories order by id desc");
    }
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $stmt->bind_result($id, $title, $text, $rating, $usr_id);
    echo "\n";
    while ($stmt->fetch()) {
        echo "
        <br />
        <form action='comments.php' method='POST'>
        <input type='hidden' name='story_user_id' value='$usr_id'/>
        <input type='hidden' name='story_id' value='$id'/>
        <input type='submit' class ='stories' name='stories' value='$title'/>
        </form> Rating:$rating <br />
        ";
        if (isset($_SESSION['user_id'])) {
            /* LIKE AND DISLIKE */
            echo "<form action='rating.php' method='POST'>
            <input type='hidden' name='like_dislike' value='1'/>
            <input type='hidden' name='story_id' value='$id'/>
            <input type='hidden' name='token' value='$token' />
            <input type='submit' class='rating' value='Like'/>
            </form>
            
            <form action='rating.php' method='POST'>
            <input type='hidden' name='like_dislike' value='-1'/>
            <input type='hidden' name='story_id' value='$id'/>
            <input type='hidden' name='token' value='$token' />
            <input type='submit'  value='Dislike'/>
            </form>";
        }
        if ($_SESSION['user_id'] == $usr_id) {
            echo "
            <form action='delete.php' method='POST'>
            <input type='hidden' name='table' value='stories'/>
            <input type='hidden' name='id' value='$id'/>
            <input type='hidden' name='token' value='$token' />
            <input type='submit' value='DELETE'/>
            </form>

            <form action='edit.php' method='POST'>
            <input type='hidden' name='table' value='stories'/>
            <input type='hidden' name='id' value='$id'/>
            <input type='hidden' name='token' value='$token' />
            <input type='submit' value='EDIT'/>
            </form>
            ";
        }
        echo "<br />";
    }

    $stmt->close();

    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: NewsSite.php");
    }
    ?>
</body>

</html>