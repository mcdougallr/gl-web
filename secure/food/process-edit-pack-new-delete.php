<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

if (isset($_POST['pack_id'])) {
	$stmt = $conn -> prepare("DELETE FROM fd_packs WHERE pack_id = :pack_id");
	$stmt -> bindValue(':pack_id', $_POST['pack_id']);
	$stmt -> execute();			
}
