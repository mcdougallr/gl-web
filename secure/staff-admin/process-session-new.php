<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/functions.php');

$insertdata['sd_session_id'] = cleantext($_POST['sd_session_id']);		
$insertdata['sd_start'] = cleantext($_POST['sd_start']);
$insertdata['sd_end'] = cleantext($_POST['sd_end']);
$insertdata['sd_description'] = cleantext($_POST['sd_description']);
$insertdata['sd_percentage'] = cleantext($_POST['sd_percentage']);

$stmt = $conn -> prepare("INSERT INTO gl_staff_session_days
												(sd_description,sd_percentage,sd_session_id,sd_start,sd_end)
												VALUES 
												(:sd_description,:sd_percentage,:sd_session_id,:sd_start,:sd_end)");

$stmt -> bindValue(':sd_description', $insertdata['sd_description']);
$stmt -> bindValue(':sd_percentage', $insertdata['sd_percentage']);
$stmt -> bindValue(':sd_session_id', $insertdata['sd_session_id']);
$stmt -> bindValue(':sd_start', $insertdata['sd_start']);
$stmt -> bindValue(':sd_end', $insertdata['sd_end']);

$stmt -> execute();