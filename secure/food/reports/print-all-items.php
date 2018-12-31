<!DOCTYPE html>
<head>
<link href="_foodprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Master Food List</title>

<?php
	session_start();
	
include ('../../shared/dbconnect.php');
include ('../../shared/functions.php');
include ('../../shared/authenticate.php');
?>
</head>
<body>
	
	<div style="text-align: center;">
		<h1 style="display: inline;">Gould Lake Outdoor Centre</h1>
		<img src="../../shared/sunman-black.png" width="30"/>
	</div>
	
	<?php
	$catnew = "";
	$catold = "";
	$first = 1;
	
	$itemquery = "SELECT * FROM fd_items
					LEFT JOIN fd_suppliers ON fd_suppliers.supplier_id = fd_items.item_supplier_id
					ORDER BY food_order_type, item_name";
	
	foreach ($conn->query($itemquery) as $item)
	{
		$catnew = $item['food_order_type'];
		if ($catold != $catnew) 
		{
			if ($first != 1) {print "</table><br />";}?>
			<h2><?php print $catnew; ?></h2>
			<table width=700 border=1 align=center>
		  	<tr>
		  		<th width="40%">Item Name</th>
			  	<th width="20%">Item Unit</th>
			  	<th width="20%">Nut Item?</th>			
			  	<th width="20%">Supplier</th>	
			  </tr>			  
		 <?php } ?>
		 	<tr class="masterlist">
		  	<td><?php print $item['item_name']; ?></td>
		  	<td align="center"><?php print $item['item_unit']; ?></td>
		  	<td align="center"><?php print $item['nut_item']; ?></td>
		  	<td align="center"><?php print $item['supplier_name']; ?></td>
		  </tr>
	 		<?php 
			$catold = $catnew;
			$first = 0;
		} ?>
 	</table>
	<br />
</body>
</html>
