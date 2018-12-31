<?php
function required_eq ($item_id, $conn)
{
	$total_num = 0;	
	
	$stmt = $conn->prepare("SELECT pack_id, pack_num_session FROM eq_packs WHERE pack_type = 'P'");
	$stmt->execute();
	$packs = $stmt->fetchAll();

	foreach($packs as $pack)
	{
		$stmt = $conn->prepare("SELECT pack_item_num FROM eq_pack_items 
														WHERE pack_item_item_id = :pack_item_item_id 
															AND pack_item_pack_id = :pack_item_pack_id 
															AND pack_item_dnc = 0");
		$stmt->bindValue(':pack_item_item_id', $item_id);
		$stmt->bindValue(':pack_item_pack_id', $pack['pack_id']);
		$stmt->execute();
		$pack_num = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$pack_nums = $pack_num['pack_item_num'] * $pack['pack_num_session'];

		$total_num = $total_num + $pack_nums;

	}

	return $total_num;
}
?>