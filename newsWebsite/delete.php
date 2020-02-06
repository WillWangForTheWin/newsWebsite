<?php
require 'Connection.php';
session_start();
$table = $_POST['table'];
$id = $_POST['id'];

$safe_table = $mysqli->real_escape_string($table);
$safe_id = $mysqli->real_escape_string($id);

if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}

$stmt = $mysqli->prepare("delete from $safe_table where id=$safe_id");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->execute();
$stmt->close();
if($table == "Comments"){
    header('Location: http://ec2-52-15-237-123.us-east-2.compute.amazonaws.com/~clay/News/comments.php');
}else{
    header('Location: http://ec2-52-15-237-123.us-east-2.compute.amazonaws.com/~clay/News/NewsSite.php');
}
