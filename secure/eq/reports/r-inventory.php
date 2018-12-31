<!DOCTYPE html>
<head>
<link href="_reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="en">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
<script src="../../../scripts/jquery/jquery-1.11.0.min.js"></script>
<script src="../../shared/jquery-ui-1.10.3.custom.js"></script>
<title>Inventory Report</title>
</head>
<body>
	<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');	
	include ("../required.php");

if (isset($_GET['tid'])) {$type_id = $_GET['tid'];}
else {$type_id = "29";}
?>

<div id="item-table">
	<?php
	$newtype = "";
	$oldtype = "";
	$rowcolour = "#F2F2F2";
	$stmt = $conn->prepare("SELECT * FROM eq_item_types WHERE item_type_id = :item_type_id");
	$stmt->bindValue(':item_type_id', $type_id);
	$stmt->execute();
	$type = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$stmt = $conn->prepare("SELECT * FROM eq_items
													LEFT JOIN eq_item_types ON eq_item_types.item_type_id = eq_items.item_type_id
													WHERE eq_items.item_type_id = :item_type_id ORDER BY item_name"); 
	$stmt->bindValue(':item_type_id', $type_id);
	$stmt->execute();
	$items = $stmt->fetchAll();
	
	$rowcolour = "#F2F2F2";	
	?>
	<h1><?php print $type['item_type_name']; ?><i id="toggle-programs" style="margin-left: 5px;font-size: .7em;vertical-align: 2px;" class="fa fa-eye"></i></h1>	
	<table>
		<tr>
			<th class="item-title">Item</th>
			<th style="text-align: center;">Req</th>
			<th style="text-align: center;"><i class="fa fa-thumbs-up" style="color: #060;"></i></th>
			<th style="text-align: center;"><i class="fa fa-hand-paper-o" style="color: #FCFF00;"></i></th>
			<th style="text-align: center;"><i class="fa fa-thumbs-down" style="color: #FF0D00;"></i></th>
			<th style="text-align: center;">Inv Date</th>
			<?php
				$stmt = $conn->prepare("SELECT pack_name FROM eq_packs WHERE pack_col_name != '0' ORDER BY pack_sort"); 
				$stmt->execute();
				$packs = $stmt->fetchAll();
				foreach($packs as $pack) {print "<th style=\"text-align: center;\" class=\"col-hide\">".$pack['pack_name']."</th>";}
			?>
		</tr>			
		<?php	
							
		foreach($items as $item)
	  	{
  			if ($item['item_type_type'] == "P") {$required = required_eq($item['item_id'], $conn);}
			else {$required = $item['item_required'];}
			
			if ($rowcolour == "#F2F2F2") {$rowcolour = "#FFF";}
			else {$rowcolour = "#F2F2F2";}
			?>
			<tr style="background: <?php print $rowcolour; ?>">
				<td class="item-title"><?php print $item['item_name']; ?></td>
				<td style="text-align: center;"><?php print $required; ?></td>
				<td style="text-align: center;"><?php print $item['item_inv_g2g']; ?></td>
				<td style="text-align: center;"><?php print $item['item_inv_minor']; ?></td>
				<td style="text-align: center;"><?php print $item['item_inv_major']; ?></td>
				<td style="text-align: center;"><?php print $item['item_inventory_date']; ?></td>
				<?php
					$stmt = $conn->prepare("SELECT pack_id, pack_name, pack_num_session FROM eq_packs WHERE pack_col_name != '0' ORDER BY pack_sort"); 
					$stmt->execute();
					$packs = $stmt->fetchAll();
					foreach($packs as $pack) 
					{
						$stmt = $conn->prepare("SELECT * FROM eq_pack_items
																	WHERE pack_item_item_id = :pack_item_item_id AND pack_item_pack_id = :pack_item_pack_id");
						$stmt->bindValue(':pack_item_item_id', $item['item_id']);
						$stmt->bindValue(':pack_item_pack_id', $pack['pack_id']);
						$stmt->execute();
						$pack_item = $stmt->fetch(PDO::FETCH_ASSOC);
						
						$pack_num = $pack_item[pack_item_num];
						$total_pack_num = $pack_item[pack_item_num] * $pack['pack_num_session'];
						
						if ($pack_num != 0)
						{
							print "<td class=\"col-hide\" style=\"text-align: center;";
							if ($pack_item['pack_item_dnc'] == 1) {print "font-weight: bold;background-color: #999;";}
							print "\">".$pack_num." / ".$total_pack_num."</td>";
						}
						else {print "<td class=\"col-hide\"></td>";}
					}
				?>
			</tr>
			<?php
		}
		?>
	</table>
</div>

</body>
</html>


<script>
	$("#toggle-programs").click(function () {
		$(".col-hide").toggle();
	});

</script>