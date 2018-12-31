<!DOCTYPE html>
<head>
	<link href="_foodprint.css" rel="stylesheet" type="text/css" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>All Foodland Orders</title>
	
	<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');
	?>

</head>

<body>
<?php
$packquery = "SELECT * FROM fd_packs 
				LEFT JOIN ss_session_sections ON ss_session_sections.section_id = fd_packs.pack_section_id
				LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_session_sections.section_session_id
				WHERE pack_type = 'Fresh'
				ORDER BY pack_date, session_sortorder";

foreach ($conn->query($packquery) as $pack)
{
	$pack_id = $pack['pack_id'];
	?>
	<div class="pagebreak">
		<div style="text-align: center;">
			<h1 style="display: inline;">Gould Lake Outdoor Centre</h1>
			<img src="../../shared/sunman-black.png" width="30"/>
		</div>
		<?php $pickup = date("D F j, Y",strtotime($pack['pack_date'])); ?>
	  <h2><?php print "Foodland";?></h2>
	  <h3><?php print "Pickup Date: ".$pickup. "<br />".$pack['session_program_code']. $pack['session_number']." - ".$pack['pack_name']." - ".$pack['pack_groups_in_session']." Food Groups";?></h3>
	  <table width=700 border=1 align=center>			
			<?php
				$stmt = $conn->prepare("SELECT * FROM fd_orders  
										LEFT JOIN fd_packs ON fd_orders.order_pack_id = fd_packs.pack_id
										LEFT JOIN fd_items ON fd_items.item_id = fd_orders.order_item_id
										WHERE order_pack_id = :order_pack_id and item_supplier_id = 2
										ORDER BY item_name");
				$stmt->bindValue(':order_pack_id', $pack_id);
				$stmt->execute();
				$result = $stmt->fetchAll();
				foreach ($result as $orderinfo)
				{	
					if ($orderinfo['order_amount'] != 0)
					{
						$total_amount = $orderinfo['order_amount'] * $orderinfo['pack_groups_in_session'];
						print "<tr height=25>";
						print "<td align=left width=300>".$orderinfo['item_name']."</td>";
						print "<td align=center width=100>".$total_amount." ".$orderinfo['item_unit']."</td>";
						print "</tr>";
					}
				}
		   ?>
		</table>
	</div>
<?php } ?>
</body>
</html>
