<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$supplier_id = cleantext($_POST['supplier_id']);
$supplier_name = cleantext($_POST['supplier_name']);
$supplier_contact = cleantext($_POST['supplier_contact']);
$supplier_contact_num = cleantext($_POST['supplier_contact_num']);
$supplier_contact_email = cleantext($_POST['supplier_contact_email']);
$supplier_pickup_deliver = cleantext($_POST['supplier_pickup_deliver']);
$supplier_notes = cleantext($_POST['supplier_notes']);
$supplier_notes_visible = cleantext($_POST['supplier_notes_visible']);

$stmt = $conn -> prepare("UPDATE fd_suppliers SET 
							supplier_name = :supplier_name,
							supplier_contact = :supplier_contact,
							supplier_contact_num= :supplier_contact_num,
							supplier_contact_email = :supplier_contact_email,
							supplier_pickup_deliver = :supplier_pickup_deliver,
							supplier_notes = :supplier_notes,
							supplier_notes_visible = :supplier_notes_visible
							WHERE supplier_id = :supplier_id");

$stmt -> bindValue(':supplier_name', $supplier_name);
$stmt -> bindValue(':supplier_contact', $supplier_contact);
$stmt -> bindValue(':supplier_contact_num', $supplier_contact_num);
$stmt -> bindValue(':supplier_contact_email', $supplier_contact_email);
$stmt -> bindValue(':supplier_pickup_deliver', $supplier_pickup_deliver);
$stmt -> bindValue(':supplier_notes', $supplier_notes);
$stmt -> bindValue(':supplier_notes_visible', $supplier_notes_visible);
$stmt -> bindValue(':supplier_id', $supplier_id);

$stmt -> execute();

?>
