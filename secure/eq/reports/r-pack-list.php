<!DOCTYPE html>
<head>
<link href="_reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
<title>Pack List</title>
</head>
<body>
	<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');

	if (isset($_GET['pid'])) {$pack_id = $_GET['pid'];}
	else {$pack_id = "";} 

	$stmt = $conn->prepare("SELECT * FROM eq_packs WHERE pack_id = :pack_id");
	$stmt->bindValue(':pack_id', $pack_id);
	$stmt->execute();
	$pack = $stmt->fetch(PDO::FETCH_ASSOC);

	print "<h1><img src=\"../../shared/sunman-black.png\" width=30 style=\"vertical-align: -3px;margin-right: 5px;\"/>".$pack['pack_name']." Packing List</h1>";
	?>
	<table>
		<tr>
			<th width="25%">Item</th>
			<th width="15%" style="text-align: center;">#</th>
			<th width="60%">Notes</th>
		</tr>
		<?php
		$stmt = $conn->prepare("SELECT * FROM eq_items 
													LEFT JOIN eq_item_types ON eq_item_types.item_type_id = eq_items.item_type_id 
													WHERE item_type_type = :item_type_type
													ORDER BY item_type_name, item_name");
		$stmt->bindValue(':item_type_type', $pack['pack_type']);
		$stmt->execute();
		$items = $stmt->fetchAll();
			
		$item_type_new = "";
		$item_type_old = "";
		foreach ($items as $item)
		{
			$stmt = $conn->prepare("SELECT * FROM eq_pack_items
														WHERE pack_item_item_id = :pack_item_item_id AND pack_item_pack_id = :pack_item_pack_id");
			$stmt->bindValue(':pack_item_item_id', $item['item_id']);
			$stmt->bindValue(':pack_item_pack_id', $pack_id);
			$stmt->execute();
			$pack_item = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if ($pack_item['pack_item_num'] != "")
			{
				$item_type_new = $item['item_type_name'];
				if ($item_type_new != $item_type_old)
				{print "<tr><td colspan=3 class=\"td-header\">".$item['item_type_name']."</td></tr>";}
					
				?>
					<tr>
						<td width="25%"><?php print $item['item_name']; ?></td>
						<td width="15%" style="text-align: center;"><?php print $pack_item['pack_item_num']; ?></td>
						<td width="60%"><?php print $pack_item['pack_item_note']; ?></td>
					</tr>
				<?php 
				$item_type_old = $item_type_new;
			}
		}
		?>
	</table>
</body>
</html>
