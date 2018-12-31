<?php
include ("food-header.php");

if (isset($_GET['iid'])) {$item_id = cleantext($_GET['iid']);}
else {header("Location: index.php");}

$new = 0;	

if ($item_id == "new")	
{
	$stmt = $conn->prepare("INSERT INTO fd_items (item_id) VALUES (NULL);");
	$stmt->execute();
	$item_id = $conn -> lastInsertId();
	$new = 1;
}

$stmt = $conn->prepare("SELECT * FROM fd_items WHERE item_id = :item_id");
$stmt->bindValue(':item_id', $item_id);
$stmt->execute();				
$item = $stmt->fetch(PDO::FETCH_ASSOC); ?>

<link rel="stylesheet" href="_gl-food.css">

<h1><?php if ($new == 1) {print "New Item";} else {print "Update Item";} ?></h1>
<form id="item-edit-form" style="max-width: 350px;margin: 0 auto;" class="pure-form pure-form-stacked" method=post action="">
	<input type=hidden name=item_id value="<?php print $item['item_id']; ?>">
	<div class="input-padding">
		<label>Item Name</label>
		<input class="pure-input-1" type=text name=item_name value="<?php print $item['item_name']; ?>">
	</div>
	<div class="input-padding">
		<label>Item Notes</label>
		<input class="pure-input-1" type=text name=item_notes value="<?php print $item['item_notes']; ?>">
	</div>
	<div class="input-padding">
		<label>Item Unit</label>
		<input class="pure-input-1" type=text name=item_unit value="<?php print $item['item_unit']; ?>"></div>
	<div class="input-padding">
		<label>Nut Free?</label>
		<select class="pure-input-1" type="text" name="item_nut">
			<option value=""></option>
			<option value=0 <?php if($item['item_nut']==0){print "selected";}?>>No</option>
      		<option value=1 <?php if($item['item_nut']==1){print "selected";}?>>Yes</option>
        </select>
  	</div>
  	<div class="input-padding">
		<label>Gluten Free?</label>
		<select class="pure-input-1" type="text" name="item_gf">
			<option value=""></option>
			<option value=0 <?php if($item['item_gf']==0){print "selected";}?>>No</option>
      		<option value=1 <?php if($item['item_gf']==1){print "selected";}?>>Yes</option>
        </select>
  	</div>
  	<div class="input-padding">
		<label>Vegetarian?</label>
		<select class="pure-input-1" type="text" name="item_veg">
			<option value=""></option>
			<option value=0 <?php if($item['item_veg']==0){print "selected";}?>>No</option>
      		<option value=1 <?php if($item['item_veg']==1){print "selected";}?>>Yes</option>
        </select>
  	</div>
	<div class="input-padding">
		<label>Item Type</label>
		<select class="pure-input-1" type="text" name="item_order_type">
      		<option value=""></option>
      		<option <?php if($item['item_order_type']=="Bulk"){print "selected";}?>>Bulk</option>
      		<option <?php if($item['item_order_type']=="Fresh"){print "selected";}?>>Fresh</option>
      		<option <?php if($item['item_order_type']=="Cheese"){print "selected";}?>>Cheese</option>
      		<option <?php if($item['item_order_type']=="Bread"){print "selected";}?>>Bread</option>
      		<option <?php if($item['item_order_type']=="Other"){print "selected";}?>>Other</option>
        </select>
 	</div>
	<div class="input-padding">
		<label>Supplier</label>
		<select class="pure-input-1" name="item_supplier_id" onkeypress="return noenter()">
	    	<option value=""></option>
	    	<?php
	    		$supplierquery = "select * from fd_suppliers order by supplier_name";
				foreach ($conn->query($supplierquery) as $row)
				{
					print "<option value=\"".$row['supplier_id']."\"";
					if($item['item_supplier_id']==$row['supplier_id'])
						{print " selected";}
					print ">".$row['supplier_name']."</option>";
				}
			?>
		</select>
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
				$("#item-edit-form").submit();
			}
			else if (button_val === "submit"){
				$("#item-edit-form").submit();
			}
			else if (button_val === "delete"){
				if (confirm("Delete? Are you sure? All orders associate with this item will be altered!") == true)
				{
					$.ajax({
					    type: "POST",
					    url: "process-edit-item-delete.php",
					    data: {item_id : <?php print $item_id; ?>},
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
			        url: "process-edit-item-new-delete.php",
			        data: {item_id : "<?php print $item_id ?>"},
			        success: function() 
			        {window.location.href = "index.php";}   
			     });
			}
		});
		
		$('#item-edit-form').submit(function(e) {
			e.preventDefault();
			
			var senddata = $(this).serialize();
			//alert(senddata);return false;
			
			$.ajax({
			    type: "POST",
			    url: "process-edit-item-save.php",
			    data: senddata,
			    success: function() 
			    {
			    	<?php 
			    	if ($new == 0) {print "alert(\"Item Updated!\");";}
					else {print "window.location.href = \"edit-item.php?iid=".$item_id."\";";}
					?>
	        	}
	 		});	
	 	});
    
	});

</script>
