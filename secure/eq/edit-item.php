<?php
include ("eq-header.php"); 

if (isset($_GET['iid'])) {$item_id = $_GET['iid'];}
else {$item_id = "";}

if ($item_id == "")
{
	$stmt = $conn->prepare("INSERT INTO eq_items (item_type_id, item_inventory_date) VALUES (:item_type_id, :item_inventory_date);");
	$stmt->bindValue(':item_type_id', "15");
	$stmt->bindValue(':item_inventory_date', date('Y-m-d'));
	$stmt->execute();
	$item_id = $conn -> lastInsertId();
}

$stmt = $conn->prepare("SELECT * FROM eq_items
											LEFT JOIN eq_item_types ON eq_item_types.item_type_id = eq_items.item_type_id
											WHERE item_id = :item_id");
$stmt->bindValue(':item_id', $item_id);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<link rel="stylesheet" href="_edit.css">

<h1>Edit/Add Item</h1>

<form id="bus-run-form" class="pure-form pure-form-stacked" method=post>
	<div class="pure-g">
		<div class="pure-u-1 pure-u-md-1-2 pure-u-lg-1-4">
			<div class="input-padding">
				<label>Name</label>
				<input class="pure-input-1 gl_input" type="text" name="item_name" onkeypress="return noenter()" value="<?php print $item['item_name']; ?>">
			</div>
		</div>
		<div class="pure-u-1 pure-u-md-1-2 pure-u-lg-1-4">
			<div class="input-padding">
				<label>Description</label>
				<input class="pure-input-1 gl_input" type="text" name="item_description" onkeypress="return noenter()" value="<?php print $item['item_description']; ?>">
			</div>
		</div>
		<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-2 pure-u-lg-1-4">
			<div class="input-padding">
				<label>Type</label>
				<select class="pure-input-1 gl_input" name="item_type_id">
					<option value="" disabled selected>Select...</option>
					<?php
						$typesquery = "SELECT * FROM eq_item_types ORDER BY item_type_name";
						foreach ($conn->query($typesquery) as $type)
						{
							print "<option value=".$type['item_type_id'];
							if ($type['item_type_id'] == $item['item_type_id'])
								{print " selected";}
							print ">".$type['item_type_name']."</option>";
						}
					?>
				</select>
			</div>
		</div>
				<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-2 pure-u-lg-1-4">
			<div class="input-padding">
				<label>Inventory Date <i id="today-button" class="fa fa-hand-o-down"></i></label>
				<input id="item_inventory_date" class="pure-input-1 gl_input" type="date" name="item_inventory_date" value="<?php print $item['item_inventory_date']; ?>" />
			</div>
		</div>
		<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4 pure-u-lg-1-4">
			<div class="input-padding">
				<label>Required</label>
				<input class="pure-input-1 gl_input" type="number" name="item_required" value="<?php print $item['item_required']; ?>"
				<?php if($item['item_type_type'] == "P"){print "disabled";} ?> />
			</div>
		</div>
		<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4 pure-u-lg-1-4">
			<div class="input-padding">
				<label>Good to Go<i class="fa fa-thumbs-up" style="margin-left: 5px; color: #060;"></i></label>
				<input class="pure-input-1 gl_input" type="number" name="item_inv_g2g" value="<?php print $item['item_inv_g2g']; ?>"/>
			</div>
		</div>
		<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4 pure-u-lg-1-4">
			<div class="input-padding">
				<label>Minor Repair<i class="fa fa-hand-paper-o" style="margin-left: 5px; color: #FCFF00;"></i></label>
				<input class="pure-input-1 gl_input" type="number" name="item_inv_minor" value="<?php print $item['item_inv_minor']; ?>"/>
			</div>
		</div>
		<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4 pure-u-lg-1-4">
			<div class="input-padding">
				<label>Major Repair<i class="fa fa-thumbs-down" style="margin-left: 5px; color: #FF0D00;"></i></label>
				<input class="pure-input-1 gl_input" type="number" name="item_inv_major" value="<?php print $item['item_inv_major']; ?>"/>
			</div>
		</div>
		<div class="pure-u-1"style="text-align: center;">
			<button id="complete" class="plaintext-button">complete</button>
			<button id="delete" class="plaintext-button">delete</button>
		</div>				
	</form>
</div>

<script>
   
	$(document).ready(function(){				
		// PROCESS FORM INPUT
		$('.gl_input').change(function(e) {
			name = $(this).attr("name");
			val = $(this).val();			
			$(this).css('background', '#39F');
			//alert(name+" "+val);
			$.ajax({
				type : "POST",
				url : "process-input.php",
				data : {
					name : name,
					val : val,
					table : "eq_items",
					id_name : "item_id",
					id: <?php print $item_id; ?>
				},
				success : function() {
					$('.gl_input').delay(300)
				    .queue(function() {
				        $('.gl_input').css('background', 'transparent').dequeue();
				    });
				}
			});
		});
		
		$("#complete").click(function (e) {
			e.preventDefault();
			window.setTimeout(function(){window.location.href = "index.php?tid=<?php print $item['item_type_id']; ?>";},250);
		});
		
		$("#delete").click(function (e) {
			e.preventDefault();
			$.ajax({
				type : "POST",
				url : "process-eq-item-delete.php",
				data : {
					id: <?php print $item_id; ?>
				},
				success : function() {
					window.location.href = "index.php?tid=<?php print $item['item_type_id']; ?>";
				}
			});
		});
		
		$("#today-button").click(function() {
			$("#item_inventory_date").val("<?php print date("Y-m-d");?>");
			$("#item_inventory_date").css('background', '#39F');
			$.ajax({
				type : "POST",
				url : "process-input.php",
				data : {
					name : "item_inventory_date",
					val : "<?php print date("Y-m-d");?>",
					table : "eq_items",
					id_name : "item_id",
					id: <?php print $item_id; ?>
				},
				success : function() {
					$('.gl_input').delay(300)
				    .queue(function() {
				        $('.gl_input').css('background', 'transparent').dequeue();
				    });
				}
			});
		});
		
	});

</script>