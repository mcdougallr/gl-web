<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

if (isset($_POST['order_id'])) {$count = count($_POST['order_id']);}
else {$count = 0;}

$order_pack_id = cleantext($_POST['order_pack_id']);	

for ($i = 0; $i<$count; $i++)
{		
	$order_id = cleantext($_POST['order_id'][$i]);
	$order_item_id = cleantext($_POST['order_item_id'][$i]);
	$order_amount = cleantext($_POST['order_amount'][$i]);
	
	if ($order_id != 0)
	{
		if ($order_amount != 0)
		{
		 	$stmt = $conn -> prepare("UPDATE fd_orders SET 
										order_item_id = :order_item_id,
										order_pack_id = :order_pack_id,
										order_amount = :order_amount
										WHERE order_id = :order_id");
		
			$stmt -> bindValue(':order_item_id', $order_item_id);
			$stmt -> bindValue(':order_pack_id', $order_pack_id);
			$stmt -> bindValue(':order_amount', $order_amount);
			$stmt -> bindValue(':order_id', $order_id);
			
			$stmt -> execute();
		}
		else 
		{
			$stmt = $conn -> prepare("DELETE FROM fd_orders WHERE order_id = :order_id");   
		    $stmt -> bindValue(':order_id', $order_id);	   
		    $stmt -> execute();
		}
	}
	elseif ($order_amount != 0) 
	{
		$stmt = $conn -> prepare("INSERT INTO fd_orders 
									(order_item_id,order_pack_id,order_amount)
									VALUES 
									(:order_item_id,:order_pack_id,:order_amount)");
		
		$stmt -> bindValue(':order_item_id', $order_item_id);
		$stmt -> bindValue(':order_pack_id', $order_pack_id);
		$stmt -> bindValue(':order_amount', $order_amount);
			
		$stmt -> execute();			
	}
}