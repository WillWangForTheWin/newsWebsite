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
    <?php
    require 'Connection.php';
    session_start();
    $token=$_SESSION['token'];
    $story_user_id = $_POST['story_user_id'];
    if (isset($_POST['story_id'])) {
        $_SESSION['story_id'] = $_POST['story_id'];
    }
    $story_id = $_SESSION['story_id'];
    $user_id = $_SESSION['user_id'];
    $comment_text = $_POST['text'];

    /* Putting Comment into table */
    if (isset($_POST['text'])) {
        $stmt = $mysqli->prepare("insert into Comments (text,user_id,story_id) values (?,?,?)");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $safe_text = $mysqli->real_escape_string($_POST['text']);
        $safe_user_id = $mysqli->real_escape_string($user_id);
        $safe_story_id = $mysqli->real_escape_string($story_id);

        $stmt->bind_param('sii', $_POST['text'], $safe_user_id, $safe_story_id);
        $stmt->execute();
        $stmt->close();
        //header('Location: comments.php');
    }

    /* Sending out the title and text of the story */
    $stmt = $mysqli->prepare("select title, text, link, UserInfo.username from stories join UserInfo on (stories.user_id = UserInfo.id) where stories.id=?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $safe_story_id = $mysqli->real_escape_string($story_id);
    $stmt->bind_param(i, $safe_story_id);
    $stmt->execute();
    $stmt->bind_result($title, $story_text, $link, $story_username);
    echo "<ul>\n";
    while ($stmt->fetch()) {
        echo "<h2>$title</h2>
        <br />
        link:<a href='$link'> $link</a>
        <br />
        posted by: $story_username \n 
        \t <p>$story_text</p></li>";
    }
    echo "</ul>\n";

    $stmt->close();

    /* Posting the comments from database */
    echo "<h3> Comments </h3>";
    $stmt = $mysqli->prepare("select Comments.id, text, UserInfo.username, user_id from Comments join UserInfo on (Comments.user_id = UserInfo.id) where story_id=$safe_story_id ");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $stmt->bind_result($comment_id, $text, $commenter, $comment_user_id);
    echo "<ul>\n";
    while ($stmt->fetch()) {
        echo "<li>$commenter posted: \n 
        \t $text</li>";
        if ($_SESSION['user_id'] == $comment_user_id) {
            echo "
        <form action='delete.php' method='POST'>
        <input type='hidden' name='table' value='Comments'/>
        <input type='hidden' name='id' value='$comment_id'/>
        <input type='hidden' name='token' value='$token' />
        <input type='submit' value='DELETE'/>
        </form>
        
        <form action='edit.php' method='POST'>
        <input type='hidden' name='table' value='Comments'/>
        <input type='hidden' name='id' value='$comment_id'/>
        <input type='hidden' name='token' value='$token' />
        <input type='submit' value='EDIT'/>
        </form>";
        }
    }
    echo "</ul>\n";

    $stmt->close();


    /* Sending Comment */
    if (isset($_SESSION['user_id']) && isset($_SESSION['story_id'])) {
        echo "
        <form method='POST' id='comment'>
        <input type='hidden' name='story_id' value='$story_id'>
        <textarea form='comment' rows='6' cols='80' type='text' name='text'></textarea>
        <br />
        <input type='hidden' name='token' value='$token' />
        <input type='submit' value='Post'/>
        </form>
        ";
    }
    ?>
</body>

</html>