<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="myWebPage.css" />
</head>

<body>
    <?php
    require 'Connection.php';
    session_start();
    $token=$_SESSION['token'];
    $table = $_POST['table'];
    $id = $_POST['id'];
    $safe_id = $mysqli->real_escape_string($id);
    // Comment Editing Field
    if ($table == "Comments") {
        $stmt = $mysqli->prepare("select text from Comments where id=$safe_id");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->execute();
        $stmt->bind_result($text);
        while ($stmt->fetch()) {
            echo "<form action='update.php' method='POST' id='comment'>
            <input type='hidden' name='id' value='$id'/>
            <input type='hidden' name='table' value='Comments'/>
            <textarea form='comment' rows='6' cols='80' type='text' name='text'>$text</textarea>
            <input type='hidden' name='token' value='$token' />
            <br />
            <input type='submit' value='Post'/>
            </form>";
        }
        $stmt->close();
    }
    // Story Editing Field
    else if ($table == "stories") {
        $stmt = $mysqli->prepare("select title, text from stories where id=$safe_id");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->execute();
        $stmt->bind_result($title, $text);
        while ($stmt->fetch()) {
            echo "
            <form action='update.php' method='POST' id='story'>
        <label>Title: <input type='text' name='title' value='$title'/></label>
        <input type='hidden' name='id' value='$id'/>
        <input type='hidden' name='table' value='stories'/>
        <br />
        <label> Text: <br /><textarea form='story' rows='6' cols='80' type='text' name='text'> $text </textarea> </label>
        <input type='hidden' name='token' value='$token' />
        <br />
        <input type='submit' value='Edit' />
        </form>";
        }

        $stmt->close();
    }


    ?>
</body>

</html>