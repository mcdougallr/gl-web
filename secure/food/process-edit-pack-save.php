<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

if (isset($_POST['pack_id'])) {
	$pack_id = $_POST['pack_id'];
	if ($_POST['pack_type'] != $_POST['pack_type_old'] )
	{
		$stmt = $conn -> prepare("DELETE FROM fd_orders WHERE food_packs_id = :food_pack_id");
		$stmt -> bindValue(':food_pack_id', $pack_id);					
		$stmt -> execute();									
	}
}

$section_id = cleantext($_POST['section_id']);

$pack_name = cleantext($_POST['pack_name']);		
$pack_type = cleantext($_POST['pack_type']);
$pack_date = cleantext($_POST['pack_date']);
$pack_groups_in_session = cleantext($_POST['pack_groups_in_session']);

$stmt = $conn -> prepare("UPDATE fd_packs SET 
							pack_section_id = :pack_section_id,
							pack_name = :pack_name,
							pack_type = :pack_type,
							pack_date = :pack_date,
							pack_groups_in_session = :pack_groups_in_session			
							WHERE pack_id = :pack_id");

$stmt -> bindValue(':pack_section_id', $section_id);
$stmt -> bindValue(':pack_name', $pack_name);
$stmt -> bindValue(':pack_type', $pack_type);
$stmt -> bindValue(':pack_date', $pack_date);
$stmt -> bindValue(':pack_groups_in_session', $pack_groups_in_session);			
$stmt -> bindValue(':pack_id', $pack_id);

$stmt -> execute();		

