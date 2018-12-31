<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$pack_id = cleantext($_POST['pack_id']);

$stmt = $conn -> prepare("DELETE FROM fd_packs WHERE pack_id = :pack_id");   
$stmt -> bindValue(':pack_id', $pack_id);	   
$stmt -> execute();
$pack_id = "";

$stmt = $conn -> prepare("DELETE FROM fd_orders WHERE food_packs_id = :food_packs_id");   
$stmt -> bindValue(':food_packs_id', $pack_id);	   
$stmt -> execute();
