<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$insertdata['item_name'] = cleantext($_POST['item_name']);
$insertdata['item_unit'] = cleantext($_POST['item_unit']);
$insertdata['nut_item'] = cleantext($_POST['nut_item']);
$insertdata['food_order_type'] = cleantext($_POST['food_order_type']);
$insertdata['food_suppliers_id'] = cleantext($_POST['food_suppliers_id']);

$stmt = $conn -> prepare("INSERT INTO fd_items_new 
												(item_name,item_unit,nut_item,food_order_type,food_suppliers_id)
												VALUES 
												(:item_name,:item_unit,:nut_item,:food_order_type,:food_suppliers_id)");

$stmt -> bindValue(':item_name', $insertdata['item_name']);
$stmt -> bindValue(':item_unit', $insertdata['item_unit']);
$stmt -> bindValue(':nut_item', $insertdata['nut_item']);
$stmt -> bindValue(':food_order_type', $insertdata['food_order_type']);
$stmt -> bindValue(':food_suppliers_id', $insertdata['food_suppliers_id']);
				
$stmt -> execute();			
