<?php
include ("food-header.php");

if (isset($_GET['pid'])) {$pack_id = cleantext($_GET['pid']);$new = 0;}
else {
	if (isset($_GET['sid'])) {$section_id = cleantext($_GET['sid']);}	
	
	$stmt = $conn->prepare("INSERT INTO fd_packs (pack_section_id) VALUES (:pack_section_id);");
	$stmt->bindValue(':pack_section_id', $section_id);
	$stmt->execute();
	$pack_id = $conn -> lastInsertId();
	
	$new = 1;
}

$stmt = $conn->prepare("SELECT * FROM fd_packs 
						LEFT JOIN ss_session_sections ON ss_session_sections.section_id = fd_packs.pack_section_id
						LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_session_sections.section_session_id
						WHERE pack_id = :pack_id");
$stmt->bindValue(':pack_id', $pack_id);
$stmt->execute();
$pack = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<link rel="stylesheet" href="_gl-food.css">

<h1><?php if ($new == 1) {print "New Pack:";} else {print "Update Pack:";} print $pack['session_program_code'].$pack['session_number']." - ".$pack['section_name']?></h1>

<form id="pack-detail-form" class="pure-form pure-form-stacked" method=post action="">
	<input type="hidden" name="pack_id" value="<?php print $pack['pack_id']; ?>">
    <input type="hidden" name="section_id" value="<?php print $pack['section_id']; ?>">
    <input id="pack-type-old" type="hidden" name="pack_type_old" value="<?php print $pack['pack_type']; ?>">
	<div class="pure-g">
		<div class="pure-u-1-4">
			<div class="input-padding">
				<label>Pack Name</label>
				<input class="pure-input-1" type=text name="pack_name" value="<?php print $pack['pack_name']; ?>">
  			</div>
  		</div>
  		<div class="pure-u-1-4">
  			<div class="input-padding">
  				<label>Pack Type</label>
				<select id="pack-type-new" class="pure-input-1" type="text" name="pack_type">
		      		<option value=""></option>
		      		<option <?php if($pack['pack_type']=="Bulk"){print "selected";}?>>Bulk</option>
		      		<option <?php if($pack['pack_type']=="Fresh"){print "selected";}?>>Fresh</option>
		      		<option <?php if($pack['pack_type']=="Cheese"){print "selected";}?>>Cheese</option>
		      		<option <?php if($pack['pack_type']=="Bread"){print "selected";}?>>Bread</option>
		      		<option <?php if($pack['pack_type']=="Other"){print "selected";}?>>Other</option>
		        </select>
			</div>
		</div>
  		<div class="pure-u-1-4">
			<div class="input-padding">
  				<label>Pack Date</label>
  				<input class="pure-input-1" type=date id="pack_date" name="pack_date" value="<?php print $pack['pack_date']; ?>">
  			</div>
  		</div>
  		<div class="pure-u-1-4">
  			<div class="input-padding">
  				<label># Groups</label>
  				<input class="pure-input-1" type=text name="pack_groups_in_session" value="<?php print $pack['pack_groups_in_session']; ?>">			          			          
  			</div>
  		</div>
		<div class="pure-u-1" style="text-align: center; margin-top: 10px;">
	      	<?php 
	      	if ($new == 0) {?>
		      	<button class="plaintext-button" value="update"><i class="fa fa-check-circle"></i> update</button>
		      	<button class="plaintext-button" value="cancelold"><i class="fa fa-times"></i> cancel</button>
		   	<?php } else { ?>
		   		<button class="plaintext-button" value="submit"><i class="fa fa-check-circle"></i> submit</button>
		   		<button class="plaintext-button" value="cancelnew"><i class="fa fa-times"></i> cancel</button>		      	
		  	<?php } ?>		  		
	 	</div>
	</div>
</form>

<?php if ($new == 0)
{?>
	<hr style="margin: 10px 0;" />
	<?php
	$stmt = $conn->prepare("SELECT * FROM fd_items
							WHERE item_order_type = :item_order_type
							ORDER BY item_name");
	$stmt->bindValue(':item_order_type', $pack['pack_type']);
	$stmt->execute();
	$item_result = $stmt->fetchAll();
	$half = floor(count($item_result)/2);
	$count = 0;
	$counter = 0; ?>
		
	<form id="itemlist-form" class="pure-form" method=post action="">
		<input type=hidden name=order_pack_id value="<?php print $pack_id; ?>">
		<div class="pure-g">
			<div class="pure-u-1-2">
				<?php 
				foreach ($item_result as $item)
		    	{
		    		$stmt = $conn->prepare("SELECT * FROM fd_orders
											WHERE order_pack_id = :order_pack_id AND order_item_id = :order_item_id");
					$stmt->bindValue(':order_pack_id', $pack['pack_id']);								
					$stmt->bindValue(':order_item_id', $item['item_id']);
					$stmt->execute();
					$order = $stmt->fetch(PDO::FETCH_ASSOC);
							
					if ($counter == $half) 
					{
						print "</div><div class=\"pure-u-1-2\">";
						$counter = -2;
					} ?>
						
					<div class="pure-g">
						<div class="pure-u-1-4" style="text-align: left;margin: 2px 0;">
							<input type=hidden name=order_id[<?php print $count; ?>] value="<?php if (isset($order['order_id'])) {print $order['order_id'];} ?>">
							<input type=hidden name=order_item_id[<?php print $count; ?>] value="<?php print $item['item_id']; ?>">
							
							<input style="height: 25;" class="pure-input-1" type=text name=order_amount[<?php print $count; ?>] value="<?php if (isset ($order['order_amount'])) {print $order['order_amount'];} ?>">
						</div>
						<div class="pure-u-3-4" style="text-align: left;padding-top: 15px">
							<label style="display: inline;padding: 0 10px"><?php 
									print $item['item_name']." (".$item['item_unit'].") "; 
									if ($item['item_nut'] == 1) {print "<i style=\"margin-left: 5px;\" class=\"fa fa-product-hunt\"></i>";}
									if ($item['item_gf'] == 1) {print "<i style=\"margin-left: 5px;\" class=\"fa fa-pagelines\"></i>";}
									if ($item['item_veg'] == 1) {print "<i style=\"margin-left: 5px;\" class=\"fa fa-leaf\"></i>";}
								?></label>
						</div>
					</div>
					<?php 
					$count++;
					$counter++;
				}
				?>
			</div>
		</div>
		<div class="pure-u-1" style="text-align: center; margin-top: 10px;">
			<button class="plaintext-button" value="updateitems"><i class="fa fa-check-circle"></i> save</button>
			<button class="plaintext-button" value="cancelold"><i class="fa fa-times"></i> cancel</button>
		</div>
	</form>
<?php } 

include("food-footer.php");
?>

<script>
$(document).ready(function(){
	
	$(".plaintext-button").click(function(e){
		e.preventDefault();
		button_val = $(this).val();
		
		if (button_val === "update"){
			$("#pack-detail-form").submit();
		}
		else if (button_val === "updateitems"){
			$("#itemlist-form").submit();
		}
		else if (button_val === "submit"){
			$("#pack-detail-form").submit();
		}
		else if (button_val === "cancelold"){
			window.location.href = "edit-pack-list.php?sid=<?php print $pack['section_id']; ?>";
		}
		else if (button_val === "cancelnew"){
			$.ajax({
		        type: "POST",
		        url: "process-edit-pack-new-delete.php",
		        data: {pack_id : "<?php print $pack['pack_id'] ?>"},
		        success: function() 
		        {	
					window.location.href = "edit-pack-list.php?sid=<?php print $pack['section_id']; ?>";
		        }   
		     });
		}
	});
	
  	$("#pack-detail-form").submit(function(e){
		e.preventDefault();
		pold = $('#pack-type-old').val();
		pnew = $('#pack-type-new').val();
		//alert (pold + " " + pnew);return false;
		 
		var senddata = $(this).serialize();
 		//alert (senddata);return false;
		if (pold == pnew || pold == "")
		{
			$.ajax({
		        type: "POST",
		        url: "process-edit-pack-save.php",
		        data: senddata,
		        success: function() 
		        {	
					window.location.href = "edit-pack.php?pid=<?php print $pack['pack_id']; ?>";
		        }   
		     });
	    }
		else
		{
			if (confirm("Change Pack Type? Are you sure? All item amounts associate with this pack will be deleted!") == true)
			{
				$.ajax({
			        type: "POST",
			        url: "process-edit-pack-save.php",
			        data: senddata,
			        success: function() 
			        {	
						window.location.href = "edit-pack.php?pid=<?php print $pack['pack_id']; ?>";
			        }   
			     });
		     }
		}
  	});
  	
  	$("#itemlist-form").submit(function(e){
		e.preventDefault();
			
		var senddata = $(this).serialize();
		senddata = senddata.replace(/%5B/g, '[');
		senddata = senddata.replace(/%5D/g, ']');
     	//alert (senddata);return false;

		$.ajax({
	        type: "POST",
	        url: "process-edit-pack-items-save.php",
	        data: senddata,
	        success: function() 
	        {	
				window.location.href = "edit-pack-list.php?sid=<?php print $pack['section_id']; ?>";
	        }   
	     });        
  	});

});
</script>


