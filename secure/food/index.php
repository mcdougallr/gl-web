<?php include ("food-header.php"); ?>

<link rel="stylesheet" href="_gl-food.css">

<div id="supplier-list-container">
  	<?php
  	$i = 0;
	$supplierquery = "SELECT * FROM fd_suppliers ORDER BY supplier_name";
	
	foreach ($conn->query($supplierquery) as $supplier)
    {
		?>
		<div class="supplier-list">
			<h2><?php print $supplier['supplier_name']; ?></h2>
			<div class="supplier-name"><strong>Contact: </strong><?php print $supplier['supplier_contact']; ?></div>
			<div class="supplier-name"><strong>Phone: </strong><?php print $supplier['supplier_contact_num']; ?></div>
			<div class="supplier-name"><strong>Email: </strong><?php print $supplier['supplier_contact_email']; ?></div>	
			<div class="supplier-name"><strong>Notes: </strong><?php print $supplier['supplier_notes_visible']; ?></div>			
  		</div>
  	<?php
	}
	?>
</div>
	
<?php include("food-footer.php"); ?>
