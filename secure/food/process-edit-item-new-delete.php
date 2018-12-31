<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$item_id = $_POST['item_id'];

if (isset($_POST['item_id'])) {

	$stmt = $conn -> prepare("DELETE FROM fd_items WHERE item_id = :item_id");   
	$stmt -> bindValue(':item_id', $item_id);	   
	$stmt -> execute();
	
}