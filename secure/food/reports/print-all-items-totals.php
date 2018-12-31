<!DOCTYPE html>
<head>
<link href="_foodprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Master Food List</title>

<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/clean.php');
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
							WHERE item_order_type != 'Fresh'
							ORDER BY item_order_type, item_name";
	
	foreach ($conn->query($itemquery) as $item)
	{
		$stmt = $conn->prepare("SELECT * FROM fd_orders
								LEFT JOIN fd_packs ON fd_packs.pack_id = fd_orders.order_pack_id
								LEFT JOIN ss_session_sections ON ss_session_sections.section_id = fd_packs.pack_section_id
								LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_session_sections.section_session_id
								WHERE order_item_id = :order_item_id");
		$stmt->bindValue(':order_item_id', $item['item_id']);
		$stmt->execute();
		$orders = $stmt->fetchAll();
		$amount_total = 0;
		foreach ($orders as $order)
		{
			if ($order['order_amount'] != 0){$amount_total = $amount_total + ($order['order_amount']*$order['pack_groups_in_session']);}
		}	
		
		$catnew = $item['item_order_type'];
		if ($catold != $catnew) 
		{
			if ($first != 1) {print "</table><br />";}?>
			<h2><?php print $catnew; ?></h2>
			<table width=700 border=1 align=center>
		  	<tr>
		  		<th width="40%">Item Name</th>
			  	<th width="20%">Amount Required</th>		
			  </tr>			  
		 <?php
		} ?>
        <tr class="masterlist">
            <td><?php print $item['item_name']; ?></td>
            <td align="center">
                <?php 
                    if ($item['item_unit'] == "g")
                        {print ($amount_total/1000)." kg";}
                    elseif ($item['item_unit'] == "ml")
                        {print ($amount_total/1000)." L";}
                    else
                        {print $amount_total." ".$item['item_unit'];}
                ?>
            </td>
      	</tr>
		<?php 
        $catold = $catnew;
        $first = 0;
		$amount_total = 0;
	} ?>
 	</table>
	<br />
</body>
</html>
