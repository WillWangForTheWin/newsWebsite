<?php
require 'Connection.php';
session_start();
$table = $_POST['table'];
$id = $_POST['id'];
$title = $_POST['title'];
$text = $_POST['text'];

$safe_title = $mysqli->real_escape_string($title);
$safe_text = $mysqli->real_escape_string($text);
$safe_id = $mysqli->real_escape_string($id);
if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    die("Request forgery detected");
}
if ($table == "stories") {
    $stmt = $mysqli->prepare("update stories set title=?, text=? where id=?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param(ssi, $safe_title, $safe_text, $safe_id);
    $stmt->execute();
    $stmt->close();
    header('Location: http://ec2-52-15-237-123.us-east-2.compute.amazonaws.com/~clay/News/NewsSite.php');
} else if ($table == "Comments") {
    $stmt = $mysqli->prepare("update Comments set text=? where id=?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param(si, $safe_text, $safe_id);
    $stmt->execute();
    $stmt->close();
    header('Location: http://ec2-52-15-237-123.us-east-2.compute.amazonaws.com/~clay/News/comments.php');
}
