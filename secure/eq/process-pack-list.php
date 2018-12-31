<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

if (isset($_POST['pack_item_id'])) {$count = count($_POST['pack_item_id']);}
else {$count = 0;}

$pack_id = cleantext($_POST['pack_item_pack_id']);

for ($i = 0; $i < $count; $i++) 
{
	$id = cleantext($_POST['pack_item_id'][$i]);
	$num = cleantext($_POST['pack_item_num'][$i]);
	$item_id = cleantext($_POST['pack_item_item_id'][$i]);
	$note = cleantext($_POST['pack_item_note'][$i]);
	$dnc = cleantext($_POST['pack_item_dnc'][$i]);
	
	if ($id != 0)
	{
		if ($num != 0 OR $note != "")
		{
		 	$stmt = $conn -> prepare("UPDATE eq_pack_items SET 
															pack_item_num = :pack_item_num,
															pack_item_dnc = :pack_item_dnc,
															pack_item_note = :pack_item_note
															WHERE pack_item_id = :pack_item_id");
		
			$stmt -> bindValue(':pack_item_id', $id);
			$stmt -> bindValue(':pack_item_note', $note);
			$stmt -> bindValue(':pack_item_num', $num);
			$stmt -> bindValue(':pack_item_dnc', $dnc);
			
			$stmt -> execute();
		}
		else 
			{
				$stmt = $conn -> prepare("DELETE FROM eq_pack_items WHERE pack_item_id = :pack_item_id");   
				$stmt -> bindValue(':pack_item_id', $id);	   
				$stmt -> execute();
			}
		}
	elseif ($num != 0) 
	{
		$stmt = $conn -> prepare("INSERT INTO eq_pack_items 
													(pack_item_pack_id,pack_item_item_id,pack_item_num, pack_item_dnc, pack_item_note)
													VALUES 
													(:pack_item_pack_id,:pack_item_item_id,:pack_item_num, :pack_item_dnc, :pack_item_note)");
		
		$stmt -> bindValue(':pack_item_pack_id', $pack_id);
		$stmt -> bindValue(':pack_item_note', $note);
		$stmt -> bindValue(':pack_item_item_id', $item_id);
		$stmt -> bindValue(':pack_item_num', $num);
		$stmt -> bindValue(':pack_item_dnc', $dnc);
		
		$stmt -> execute();			
	}
}