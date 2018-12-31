<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$item_id = cleantext($_POST['item_id']);
$item_name = cleantext($_POST['item_name']);
$item_notes = cleantext($_POST['item_notes']);
$item_unit = cleantext($_POST['item_unit']);
$nut_item = cleantext($_POST['item_nut']);
$gf_item = cleantext($_POST['item_gf']);
$veg_item = cleantext($_POST['item_veg']);
$food_order_type = cleantext($_POST['item_order_type']);
$food_suppliers_id = cleantext($_POST['item_supplier_id']);

$stmt = $conn -> prepare("UPDATE fd_items SET 
							item_name = :item_name,
							item_notes = :item_notes,
							item_unit = :item_unit,
							item_nut = :item_nut,
							item_gf = :item_gf,
							item_veg = :item_veg,
							item_order_type = :item_order_type,
							item_supplier_id = :item_supplier_id
							WHERE item_id = :item_id");

$stmt -> bindValue(':item_name', $item_name);
$stmt -> bindValue(':item_notes', $item_notes);
$stmt -> bindValue(':item_unit', $item_unit);
$stmt -> bindValue(':item_nut', $nut_item);
$stmt -> bindValue(':item_gf', $gf_item);
$stmt -> bindValue(':item_veg', $veg_item);
$stmt -> bindValue(':item_order_type', $food_order_type);
$stmt -> bindValue(':item_supplier_id', $food_suppliers_id);
$stmt -> bindValue(':item_id', $item_id);

$stmt -> execute();