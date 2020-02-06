<?php
require 'Connection.php';
session_start();
$story_id = $_POST['story_id'];
$like_dislike = $_POST['like_dislike'];
// Get the current rating
$stmt = $mysqli->prepare("select rating from stories where id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$safe_id = $mysqli->real_escape_string($story_id);
$stmt->bind_param(i,$safe_id);
$stmt->execute();
$stmt->bind_result($rating);

while($stmt->fetch()){
    $rating = $rating + $like_dislike;
}
$stmt->close();
// update the rating
$stmt = $mysqli->prepare("update stories set rating=? where id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$safe_rating = $mysqli->real_escape_string($rating);
$safe_id = $mysqli->real_escape_string($story_id);
$stmt->bind_param(ii,$safe_rating,$safe_id);

$stmt->execute();

$stmt->close();
/*header("Location: " . $_SERVER['REQUEST_URI']);
exit;*/
header("Location: NewsSite.php");
