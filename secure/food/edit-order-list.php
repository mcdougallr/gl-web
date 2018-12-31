<?php
include ("food-header.php");
?>
<link rel="stylesheet" href="_gl-food.css">
<?php
if (isset ($_GET['sid'])) {$supplier_id = cleantext($_GET['sid']);}
else {header("Location: index.php");}
    
$stmt = $conn->prepare("SELECT * FROM fd_suppliers WHERE supplier_id = :supplier_id");
$stmt->bindValue(':supplier_id', $supplier_id);
$stmt->execute();				
$supplier = $stmt->fetch(PDO::FETCH_ASSOC);	

if ($supplier_id != "2")
{					 
	$stmt = $conn->prepare("SELECT * FROM fd_order_dates 
							WHERE order_date_supplier_id = :order_date_supplier_id
							ORDER BY order_date_start");
	$stmt->bindValue(':order_date_supplier_id', $supplier_id);
	$stmt->execute();				
	$order_dates = $stmt->fetchAll();
				
	?>
		
	<h1><?php print $supplier['supplier_name'];?></h1>

	<div id="order-table">
		<form class="pure-form pure-form-stacked" method=post action="">
			<table>
				<tr>
					<th><i class="fa fa-eye"></i></th>
					<th>Delivery/Pickup Date</th>
					<th>Catchment Start Date</th>
					<th>Catchment End Date</th>
					<th><i class="fa fa-pencil"></i></th>
					<th><i class="fa fa-print"></i></th>
				</tr>
				<?php
				$bg = "#EEE";
				foreach ($order_dates as $order)
				{      	
					if ($bg == "#EEE") {$bg = "#FFF";} else {$bg = "#EEE";} ?>
					<tr id="t-<?php print $order['order_date_id']; ?>">
						<td style="background: <?php print $bg; ?>;"><button id="v-<?php print $order['order_date_id']; ?>" class="plaintext-button view-button" value="view"><i class="fa fa-eye"></i></button></td>
						<td style="background: <?php print $bg; ?>;"><?php print date('M d, Y', strtotime($order['order_date_delivery'])); ?></td>
						<td style="background: <?php print $bg; ?>;"><?php print date('M d, Y', strtotime($order['order_date_start'])); ?></td>
						<td style="background: <?php print $bg; ?>;"><?php print date('M d, Y', strtotime($order['order_date_end'])); ?></td>
						<td style="background: <?php print $bg; ?>;">
							<a class="plaintext-button" href="edit-order.php?oid=<?php print $order['order_date_id']; ?>&sid=<?php print $supplier_id; ?>">
							<i class="fa fa-pencil"></i></a>
						</td>
						<td style="background: <?php print $bg; ?>;">
							<a target="_blank" class="plaintext-button" href="reports/print-order.php?oid=<?php print $order['order_date_id']; ?>">
							<i class="fa fa-print"></i></a>
						</td>
					</tr>
					<tr id="td-<?php print $order['order_date_id']; ?>" style="display: none;" class="pack-list">
						<td colspan=7 style="background: <?php print $bg; ?>;">
							<?php	
		   					$start = $order['order_date_start'];
							$end = $order['order_date_end'];
							
							$stmt = $conn->prepare("SELECT * FROM fd_items
													LEFT JOIN fd_orders ON fd_orders.order_item_id = fd_items.item_id
													LEFT JOIN fd_packs ON fd_orders.order_pack_id = fd_packs.pack_id
													LEFT JOIN ss_session_sections ON ss_session_sections.section_id = fd_packs.pack_section_id
													LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_session_sections.section_session_id
													WHERE item_supplier_id = :order_suppliers_id AND pack_date between :start and :end
													ORDER BY session_sortorder");
							$stmt->bindValue(':order_suppliers_id', $supplier_id);
							$stmt->bindValue(':start', $start);
							$stmt->bindValue(':end', $end);
							$stmt->execute();				
							$itemlist = $stmt->fetchAll();
							$course_list = "";
							$old_session = "";
							$new_session = "";
							$first = 1;
							
							foreach ($itemlist as $item)
							{
								$new_session = $item['pack_id'];
								if ($item['order_amount'] != 0 && $new_session != $old_session)
								{
									if ($first != 1) {$course_list = $course_list." / ";$first = 0;} else {$first = 0;}
									$course_list = $course_list.$item['session_program_code'].$item['session_number']." (".$item['pack_name'].")";
									$old_session = $item['pack_id'];									
								}
							}
							print $course_list; ?>
						</td>
					</tr>	
				<?php 
				} ?>
				<tr>
					<td colspan=7>
						<a class="plaintext-button" href="edit-order.php?oid=new&sid=<?php print $supplier_id; ?>"><i class="fa fa-plus plaintext-button"></i> Add New Order</a><br />
						<a target="_blank" class="plaintext-button" href="reports/print-order-all.php?sid=<?php print $supplier_id; ?>"><i class="fa fa-print plaintext-button"></i> Print All</a>
					</td>
				</tr>
			</table>
		</form>
	</div>
<?php
}
else
{?>
	<h1><?php print $supplier['supplier_name'];?></h1>
	<div style="text-align: center; padding: 0 5px 5px;width: 100%;">
		<a target="_blank" href="reports/print-order-foodland-all.php" class="plaintext-button" id="foodland-print-all">print all <i class="fa fa-print"></i></a>
	</div>
	<div id="order-table">
		<table>
			<tr>
				<th>Pickup Date</th>
				<th colspan=2>Food Pack Title</th>
			</tr>
	
			<?php	
			$packquery = "SELECT * FROM fd_packs 
							LEFT JOIN ss_session_sections ON ss_session_sections.section_id = fd_packs.pack_section_id
							LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_session_sections.section_session_id
							WHERE pack_type = 'Fresh' 
							ORDER BY pack_date, session_sortorder";
			
			foreach ($conn->query($packquery) as $pack)
			{?>
				<tr>
					<td><?php print $pack['pack_date']; ?></td>
					<td style="text-align: left;">
						<?php print $pack['pack_name']; ?>
					</td>
					<td>
						<a target="_blank" class="plaintext-button" href="reports/print-order-foodland.php?oid=<?php print $pack['pack_id']; ?>"><i class="fa fa-print"></i></a>
					</td>
				</tr>
			<?php
		}?>
		</table>
	</div>
	<?php
}?>

<script>
/*
(function() {  
    var elem = document.createElement('input');  
    elem.setAttribute('type', 'date');  

    if ( elem.type === 'text' ) {  
       $('#odd').datepicker({dateFormat: 'yy-mm-dd'});
       $('#ods').datepicker({dateFormat: 'yy-mm-dd'}); 
       $('#ode').datepicker({dateFormat: 'yy-mm-dd'});
    }  
 })();*/

$(document).ready(function(){
	
	$(".view-button").click(function(e){
		e.preventDefault;
		id = $(this).attr("id");
		id = id.replace("v-", "");
		id = "#td-" + id;
		//alert(id);
		$(id).toggle();
		return false;
	});	
    
});
</script>
