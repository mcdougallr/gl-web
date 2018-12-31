<!DOCTYPE html>
<head>
<link href="_foodprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Order Print</title>

<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/clean.php');
	include ('../../shared/authenticate.php');
?>
</head>
<body>
	<?php
		if (isset($_GET['oid'])) {$pack_id = cleantext($_GET['oid']);}
		else {$pack_id = "";}
		
		$stmt = $conn->prepare("SELECT * FROM fd_order_dates
								LEFT JOIN fd_suppliers ON fd_suppliers.supplier_id = fd_order_dates.order_date_supplier_id
								WHERE order_date_id = :order_date_id");
		$stmt->bindValue(':order_date_id', $pack_id);
		$stmt->execute();				
		$pack = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$supplier_id = $pack['supplier_id'];
		$start = $pack['order_date_start'];
		$end = $pack['order_date_end'];
		$course_list = "";
	?>
	<div style="text-align: center;">
		<h1 style="display: inline;">Gould Lake Outdoor Centre</h1>
		<img src="../../shared/sunman-black.png" width="30"/>
	</div>
  <h2><?php print $pack['supplier_name'] ?></h2>
  <h3><?php print $pack['supplier_pickup_deliver']." Date: ";?>
  <?php $pickup = date("D F j, Y",strtotime($pack['order_date_delivery']));
	print $pickup; ?>
	</h3>
  <table width=700 border=1 align=center>				
		<?php

			$stmt = $conn->prepare("SELECT * FROM fd_items
									LEFT JOIN fd_suppliers ON fd_items.item_supplier_id = fd_suppliers.supplier_id
									WHERE supplier_id = :supplier_id
									ORDER BY item_name");
			$stmt->bindValue(':supplier_id', $supplier_id);
			$stmt->execute();
			$result = $stmt->fetchAll();
			foreach ($result as $iteminfo)
			{
				print "<tr><td width=200 align=left>".$iteminfo['item_name']."</td><td width=100 align=center>";
				$item_id = $iteminfo['item_id'];
				$amount_total = 0;
				$course_list = "";
				$stmt = $conn->prepare("SELECT * FROM fd_orders
										LEFT JOIN fd_packs ON fd_packs.pack_id = fd_orders.order_pack_id
										LEFT JOIN ss_session_sections ON ss_session_sections.section_id = fd_packs.pack_section_id
										LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_session_sections.section_session_id
										WHERE order_item_id = :order_item_id AND pack_date BETWEEN :start AND :end");
				$stmt->bindValue(':order_item_id', $item_id);
				$stmt->bindValue(':start', $start);
				$stmt->bindValue(':end', $end);
				$stmt->execute();
				$orders = $stmt->fetchAll();
				foreach ($orders as $order)
				{
					if ($order['order_amount'] != 0)
					{
						$course_list =  $course_list.$order['session_program_code']. $order['session_number']." (".$order['pack_name'].") - ".$order['pack_groups_in_session']." Food Groups @ ".$order['order_amount']." ".$iteminfo['item_unit']."<br />";
                              $amount_total = $amount_total + ($order['order_amount']*$order['pack_groups_in_session']);

					}
					
				}
				if ($iteminfo['item_unit'] == "g")
					{print ($amount_total/1000)." kg</td>";}
				elseif ($iteminfo['item_unit'] == "ml")
					{print ($amount_total/1000)." L</td>";}
				else
					{print $amount_total." ".$iteminfo['item_unit']."</td>";}
				print "<td width=400 align=left>".$course_list."</tr>";
			}
    ?>
 	</table>
</body>
</html>
