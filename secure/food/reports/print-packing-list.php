<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="_foodprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<title>Food Pack Sheet</title>
</head>
<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/clean.php');
	include ('../../shared/authenticate.php');
	
	if (isset($_GET['sid'])) {$section_id = cleantext($_GET['sid']);}
	elseif (isset($_GET['pid'])) {$pack_id = cleantext($_GET['pid']);}
	else {header("Location: ../index.php");}

?>
<style>
	table {
		font-size: 8px;
	}
	@media print  
	{
		.print-break {
			page-break-inside: avoid;
		}
	}
</style>
<body>
	
	<div style="text-align: center;">
		<h1 style="display: inline;">Gould Lake Outdoor Centre</h1>
		<img src="../../shared/sunman-black.png" width="30"/>
	</div>
	
	<?php 
	if (isset($_GET['pid'])) {
		$stmt = $conn->prepare("SELECT * FROM fd_packs 
													LEFT JOIN ss_session_sections ON ss_session_sections.section_id = fd_packs.pack_section_id
													LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_session_sections.section_session_id
													WHERE pack_id = :pack_id");
		$stmt->bindValue(':pack_id', $pack_id);
		$stmt->execute();
		$packs = $stmt->fetchAll();	
	}
	else {
		$stmt = $conn->prepare("SELECT * FROM fd_packs 
														LEFT JOIN ss_session_sections ON ss_session_sections.section_id = fd_packs.pack_section_id
														LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_session_sections.section_session_id
														WHERE pack_section_id = :pack_section_id ORDER BY pack_name, pack_type, pack_date");
		$stmt->bindValue(':pack_section_id', $section_id);
		$stmt->execute();
		$packs = $stmt->fetchAll();	
	}
	foreach ($packs as $packinfo)
	{
	?>
 	<div class="print-break">
		<h2 style="font-size: 16px;"><?php print $packinfo['session_program_code'].$packinfo['session_number']." ".$packinfo['pack_name']."<br>Pack Date: ".$packinfo['pack_date']; ?></h2>
		<table width=50% border=1 align=center>
			<tr><th colspan=2 height="25px" style="background: #222; color: #FFF;font-size: 12px;"><?php print $packinfo['pack_type']; ?></th></tr>
			<tr>
				<td align=left width=75% style="font-weight: bold; background: #CCC;font-size: 10px;">Item</td>
				<td align=center width=25% style="font-weight: bold; background: #CCC;font-size: 10px;">Quantity</td>
			</tr>
			<?php
			$stmt = $conn->prepare("SELECT * from fd_orders 
									LEFT JOIN fd_items ON fd_items.item_id = fd_orders.order_item_id
									WHERE order_pack_id = :order_pack_id
									ORDER BY item_name");
			$stmt->bindValue(':order_pack_id', $packinfo['pack_id']);
			$stmt->execute();
			$order_results = $stmt->fetchAll();

			foreach ($order_results as $order)
			{
				if ($order['order_amount'] != 0)
				{
					print "<tr>";
					print "<td align=left>".$order['item_name'];
					if ($order['item_nut'] == 1) {print "<i style=\"margin-left: 5px;\" class=\"fa fa-product-hunt\"></i>";}
					if ($order['item_gf'] == 1) {print "<i style=\"margin-left: 5px;\" class=\"fa fa-pagelines\"></i>";}
					if ($order['item_veg'] == 1) {print "<i style=\"margin-left: 5px;\" class=\"fa fa-leaf\"></i>";}
					print "</td><td align=center>".$order['order_amount']." ".$order['item_unit'];					
					print "</td></tr>";
				}

			}
			?>
		</table>
		<br />
	</div>
	<?php
		}
	?>
</body>
</html>
