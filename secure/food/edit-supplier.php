<?php
include ("food-header.php");

if (isset($_GET['sid'])) 
	{
		$supplier_id = cleantext($_GET['sid']);
		if ($supplier_id == "new")
		{
			$stmt = $conn->prepare("INSERT INTO fd_suppliers (supplier_id) VALUES (NULL);");
			$stmt->execute();
			$supplier_id = $conn -> lastInsertId();
			$new = 1;
		}
		else {$new = 0;}
	}
else {header("Location: index.php");}

$stmt = $conn->prepare("SELECT * FROM fd_suppliers 
						WHERE supplier_id = :supplier_id");
$stmt->bindValue(':supplier_id', $supplier_id);
$stmt->execute();				
$supplier = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<link rel="stylesheet" href="_gl-food.css">

<h1><?php if ($new == 1) {print "New Supplier";} else {print "Edit Supplier";} ?></h1>
<form id="supplier-edit-form" style="max-width: 350px;margin: 0 auto;" class="pure-form pure-form-stacked" method=post action="">
		<input type=hidden name=supplier_id value="<?php print $supplier['supplier_id']; ?>">
		<div class="input-padding">
			<label>Supplier Name</label>
			<input class="pure-input-1" type=text name=supplier_name value="<?php print $supplier['supplier_name']; ?>">
		</div>
		<div class="input-padding">
			<label>Contact</label>
			<input class="pure-input-1" type=text name=supplier_contact value="<?php print $supplier['supplier_contact']; ?>">
		</div>
		<div class="input-padding">
			<label>Contact Phone</label>
			<input class="pure-input-1" type=text name=supplier_contact_num value="<?php print $supplier['supplier_contact_num']; ?>">
		</div>
		<div class="input-padding">
			<label>Contact Email</label>
			<input class="pure-input-1" type=text name=supplier_contact_email value="<?php print $supplier['supplier_contact_email']; ?>">
		</div>
		<div class="input-padding">
			<label>Pickup/Deliver?</label>
			<input class="pure-input-1" type=text name=supplier_pickup_deliver value="<?php print $supplier['supplier_pickup_deliver']; ?>">
		</div>							
		<div class="input-padding">
			<label>Notes</label>
			<textarea class="pure-input-1" name=supplier_notes rows=6><?php print $supplier['supplier_notes']; ?></textarea>
		</div>
		<div class="input-padding">
			<label>Notes for Landing Page</label>
			<textarea class="pure-input-1" name=supplier_notes_visible rows=6><?php print $supplier['supplier_notes_visible']; ?></textarea>
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
				$("#supplier-edit-form").submit();
			}
			else if (button_val === "submit"){
				$("#supplier-edit-form").submit();
			}
			else if (button_val === "delete"){
				if (confirm("Delete? Are you sure? All orders associate with this supplier will be deleted!") == true)
				{
					$.ajax({
					    type: "POST",
					    url: "process-edit-supplier-delete.php",
					    data: {supplier_id : <?php print $supplier_id; ?>},
					    success: function() {window.location = "index.php";}
					});
				}
			}
			else if (button_val === "cancelold"){
				window.location.href = "index.php";
			}
			else if (button_val === "cancelnew"){
				$.ajax({
			        type: "POST",
			        url: "process-edit-supplier-delete.php",
			        data: {supplier_id : "<?php print $supplier_id ?>"},
			        success: function() 
			        {window.location.href = "index.php";}   
			     });
			}
		});
			
	  	$('#supplier-edit-form').submit(function(e) {
			e.preventDefault();
		  	
	     	var senddata = $(this).serialize();
			//alert (senddata);return false;

			$.ajax({
			    type: "POST",
			    url: "process-edit-supplier-save.php",
			    data: senddata,
			    success: function() 
			    {
			    	<?php 
			    	if ($new == 0) {print "alert(\"Supplier Updated!\");";}
					else {print "window.location.href = \"edit-supplier.php?iid=".$supplier_id."\";";}
					?>    
	        	}  
		 	});   
		});
    
	});
	
</script>
