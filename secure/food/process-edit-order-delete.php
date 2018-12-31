<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$order_date_id = cleantext($_POST['order_id']);

$stmt = $conn -> prepare("DELETE FROM fd_order_dates WHERE order_date_id = :order_date_id");   
$stmt -> bindValue(':order_date_id', $order_date_id);	   
$stmt -> execute();