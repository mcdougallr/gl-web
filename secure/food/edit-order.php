<?php
include ("food-header.php");

if (isset($_GET['oid'])) {$order_id = cleantext($_GET['oid']);}
else {header("Location: index.php");}

if (isset($_GET['sid'])) {$supplier_id = cleantext($_GET['sid']);}
else {header("Location: index.php");}

$new = 0;	

if ($order_id == "new")	
{
	$stmt = $conn->prepare("INSERT INTO fd_order_dates (order_date_supplier_id) VALUES (:order_date_supplier_id)");
	$stmt->bindValue(':order_date_supplier_id', $supplier_id);
	$stmt->execute();
	$order_id = $conn -> lastInsertId();
	$new = 1;
}

$stmt = $conn->prepare("SELECT * FROM fd_order_dates WHERE order_date_id = :order_date_id");
$stmt->bindValue(':order_date_id', $order_id);
$stmt->execute();				
$order = $stmt->fetch(PDO::FETCH_ASSOC); 
?>

<link rel="stylesheet" href="_gl-food.css">

<h1><?php if ($new == 1) {print "New Order Date";} else {print "Update Order Date";} ?></h1>
<form id="order-edit-form" style="max-width: 350px;margin: 0 auto;" class="pure-form pure-form-stacked" method=post action="">
	<input type=hidden name="order_date_id" value="<?php print $order['order_date_id']; ?>">
	<input type=hidden name="order_date_supplier_id" value="<?php print $supplier_id; ?>">
	<div class="input-padding">
		<label>Delivery/Pickup Date</label>
		<input class="pure-input-1" type="date" name="order_date_delivery" value="<?php print $order['order_date_delivery']; ?>">
	</div>
	<div class="input-padding">
		<label>Catchment Start Date</label>
		<input class="pure-input-1" type="date" name="order_date_start" value="<?php print $order['order_date_start']; ?>">
	</div>
	<div class="input-padding">
		<label>Catchment End Date</label>
		<input class="pure-input-1" type="date" name="order_date_end" value="<?php print $order['order_date_end']; ?>">
	</div>
	<div style="text-align: center;">
		<?php 
      	if ($new == 0) {?>
	      	<button class="plaintext-button" value="update"><i class="fa fa-check-circle"></i> update</button>
	      	<button class="plaintext-button" value="delete"><i class="fa fa-trash"></i> delete</button>
	      	<button class="plaintext-button" value="cancelold"><i class="fa fa-times"></i> cancel</button>
	   	<?php } else { ?>
	   		<button class="plaintext-button" value="submit"><i class="fa fa-check-circle"></i> submit</button>
	   		<button class="plaintext-button" value="cancelnew"><i class="fa fa-times"></i> cancel</button>		      	
	  	<?php } ?>	
	</div>			
</form>

<script>
	$(document).ready(function(){
		
		$(".plaintext-button").click(function(e){
			e.preventDefault();
			button_val = $(this).val();
			//alert(button_val);return false;
			
			if (button_val === "update"){
				$("#order-edit-form").submit();
				return false;
			}
			else if (button_val === "submit"){
				$("#order-edit-form").submit();
				return false;
			}
			else if (button_val === "delete"){
				if (confirm("Delete? Are you sure?") == true)
				{
					$.ajax({
					    type: "POST",
					    url: "process-edit-order-delete.php",
					    data: {order_id : <?php print $order_id; ?>},
					    success: function() {window.location.href = "edit-order-list.php?sid=<?php print $supplier_id; ?>";}
					});
					return false;
				}
			}
			else if (button_val === "cancelold"){
				window.location.href = "edit-order-list.php?sid=<?php print $supplier_id; ?>";
			}
			else if (button_val === "cancelnew"){
				$.ajax({
			        type: "POST",
			        url: "process-edit-order-delete.php",
			        data: {order_id : "<?php print $order_id ?>"},
			        success: function() 
			        {window.location.href = "edit-order-list.php?sid=<?php print $supplier_id; ?>";}   
			     });
			     return false;
			}
		});
		
		$('#order-edit-form').submit(function(e) {
			e.preventDefault();
			
			var senddata = $(this).serialize();
			//alert(senddata);return false;
			
			$.ajax({
			    type: "POST",
			    url: "process-edit-order-save.php",
			    data: senddata,
			    success: function() 
			    {
			    	window.location.href = "edit-order-list.php?sid=<?php print $supplier_id; ?>";
	        	}
	 		});	
	 		return false;
	 	});
    
	});

</script>
