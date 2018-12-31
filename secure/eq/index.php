<?php 	
	include ("eq-header.php");
	include ("required.php");
?>

<link rel="stylesheet" href="_index.css">

<?php
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
	<h1><?php print $type['item_type_name']; ?></h1>	
	<table>
		<tr>
			<th class="item-title">Item</th>
			<th class="col-hide">Description</th>
			<th style="text-align: center;">Req</th>
			<th style="text-align: center;"><i class="fa fa-thumbs-up" style="color: #060;"></i></th>
			<th style="text-align: center;"><i class="fa fa-hand-paper-o" style="color: #FCFF00;"></i></th>
			<th style="text-align: center;"><i class="fa fa-thumbs-down" style="color: #FF0D00;"></i></th>
			<th style="text-align: center;">Inv Date</th>
		</tr>			
		<?php	
							
		foreach($items as $item)
	  	{
  			$required = 0;
			if ($item['item_type_type'] == "P") {$required = required_eq($item['item_id'], $conn);}
			else {$required = $item['item_required'];}
			
			if ($rowcolour == "#F2F2F2") {$rowcolour = "#FFF";}
			else {$rowcolour = "#F2F2F2";}
			?>
			<tr style="background: <?php print $rowcolour; ?>">
				<td class="item-title">
					<a href="edit-item.php?iid=<?php print $item['item_id']; ?>"><?php print $item['item_name']; ?></a>
		  		</td>
				<td class="col-hide"><?php print $item['item_description']; ?></td>
				<td style="text-align: center;"><?php print $required; ?></td>
				<td style="text-align: center;"><?php print $item['item_inv_g2g']; ?></td>
				<td style="text-align: center;"><?php print $item['item_inv_minor']; ?></td>
				<td style="text-align: center;"><?php print $item['item_inv_major']; ?></td>
				<td style="text-align: center;"><?php print $item['item_inventory_date']; ?></td>
			</tr>
			<?php
		}
		?>
	</table>
</div>
	
<?php include("eq-footer.php"); ?>

<script>

</script>