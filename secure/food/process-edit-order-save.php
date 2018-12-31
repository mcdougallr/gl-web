<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$order_date_id = cleantext($_POST['order_date_id']);
$order_date_supplier_id = cleantext($_POST['order_date_supplier_id']);
$order_date_delivery = cleantext($_POST['order_date_delivery']);
$order_date_start = cleantext($_POST['order_date_start']);
$order_date_end = cleantext($_POST['order_date_end']);

$stmt = $conn -> prepare("UPDATE fd_order_dates SET 
							order_date_supplier_id = :order_date_supplier_id,
							order_date_delivery = :order_date_delivery,												
							order_date_start = :order_date_start,
							order_date_end = :order_date_end
							WHERE order_date_id = :order_date_id");

$stmt -> bindValue(':order_date_id', $order_date_id);
$stmt -> bindValue(':order_date_supplier_id', $order_date_supplier_id);
$stmt -> bindValue(':order_date_delivery', $order_date_delivery);
$stmt -> bindValue(':order_date_start', $order_date_start);
$stmt -> bindValue(':order_date_end', $order_date_end);

$stmt -> execute();