<?php
session_start();
include ('../shared/dbconnect.php');

$info1 = $_POST['news_info1'];
$link1 = $_POST['news_link1'];
$info2 = $_POST['news_info2'];
$link2 = $_POST['news_link2'];
$info3 = $_POST['news_info3'];
$link3 = $_POST['news_link3'];

$stmt = $conn -> prepare("UPDATE gl_news SET news_info = :news_info, news_link = :news_link WHERE news_id = :news_id");
$stmt -> bindValue(':news_info', $info1);
$stmt -> bindValue(':news_link', $link1);
$stmt -> bindValue(':news_id', "1");
$stmt -> execute();

$stmt = $conn -> prepare("UPDATE gl_news SET news_info = :news_info, news_link = :news_link WHERE news_id = :news_id");
$stmt -> bindValue(':news_info', $info2);
$stmt -> bindValue(':news_link', $link2);
$stmt -> bindValue(':news_id', "2");
$stmt -> execute();

$stmt = $conn -> prepare("UPDATE gl_news SET news_info = :news_info, news_link = :news_link WHERE news_id = :news_id");
$stmt -> bindValue(':news_info', $info3);
$stmt -> bindValue(':news_link', $link3);
$stmt -> bindValue(':news_id', "3");
$stmt -> execute();

header("Location: edit-newsfeed.php");