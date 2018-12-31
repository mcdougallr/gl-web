<?php
include ("eq-header.php"); 

if (isset($_GET['pid'])) {$pack_id = $_GET['pid'];}
else {$pack_id = "";}

if ($pack_id == "")
{
	$stmt = $conn->prepare("INSERT INTO eq_packs (pack_type) VALUES (:pack_type);");
	$stmt->bindValue(':pack_type', "P");
	$stmt->execute();
	$pack_id = $conn -> lastInsertId();
}

$stmt = $conn->prepare("SELECT * FROM eq_packs WHERE pack_id = :pack_id");
$stmt->bindValue(':pack_id', $pack_id);
$stmt->execute();
$pack = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<link rel="stylesheet" href="_edit.css">
<style>
@media screen and (max-width: 567px){
	.hide-small {
		display: none;
	}
}
</style>

<div style="max-width: 800px;margin: 0 auto;">
	<h1>Edit/Add Pack Info</h1>
	<form id="pack-form" class="pure-form pure-form-stacked" method=post>
		<div class="pure-g">
			<div class="pure-u-1 pure-u-sm-1-3">
				<div class="input-padding">
					<label>Name</label>
					<input class="pure-input-1 gl_input" type="text" name="pack_name" onkeypress="return noenter()" value="<?php print $pack['pack_name']; ?>">
				</div>
			</div>
			<div class="pure-u-1-2 pure-u-sm-1-3">
				<div class="input-padding">
					<label>Type</label>
					<select class="pure-input-1 gl_input" name="pack_type">
						<option value="P" <?php if ($pack['pack_type'] == "P") {print "selected";}?>>Pack</option>
						<option value="S" <?php if ($pack['pack_type'] == "S") {print "selected";}?>>Supply</option>
					</select>
				</div>
			</div>
			<div class="pure-u-1-2 pure-u-sm-1-3">
				<div class="input-padding">
					<label># of Groups</label>
					<input class="pure-input-1 gl_input" type="text" name="pack_num_session" onkeypress="return noenter()" value="<?php print $pack['pack_num_session']; ?>">
				</div>
			</div>
			<div class="pure-u-1">
				<div class="input-padding">
					<label>Notes</label>
					<textarea class="pure-input-1 gl_input" type="text" name="pack_note"><?php print $pack['pack_note']; ?></textarea>
				</div>
			</div>
			<div class="pure-u-1"style="text-align: center;">
				<button id="complete" class="plaintext-button">complete</button>
				<button id="delete" class="plaintext-button">delete</button>
				<button id="print" class="plaintext-button">print</button>
			</div>				
		</div>
	</form>
	<hr />
	<h1>Pack List</h1>
	<form id="pack-list-form" class="pure-form pure-form-stacked" method=post>
		<input type="hidden" name="pack_item_pack_id" value="<?php print $pack_id; ?>">
		<div class="pure-g">
		<?php
		$stmt = $conn->prepare("SELECT * FROM eq_items 
												LEFT JOIN eq_item_types ON eq_item_types.item_type_id = eq_items.item_type_id 
												WHERE item_type_type = :item_type_type
												ORDER BY item_type_name, item_name");
		$stmt->bindValue(':item_type_type', $pack['pack_type']);
		$stmt->execute();
		$items = $stmt->fetchAll();
		
		$count = 0;
		$typecount = 0;
		$item_type_new = "";
		$item_type_old = "";
		foreach ($items as $item)
		{
			$item_type_new = $item['item_type_name'];
			if ($item_type_new != $item_type_old)
			{
				if ($typecount != 0) {print "</div>";}
				$typecount = 1;?>
				<div class="type-list">
					<div class="pure-u-1">
						<i class="fa fa-floppy-o pack-save"></i>
						<h2><?php print $item['item_type_name']; ?></h2>						
					</div>
					<div class="pure-u-1-2 pure-u-sm-1-3"><label>Item</label></div>
		  			<div class="pure-u-1-4 pure-u-sm-1-6"><label style="text-align: center;">#</label></div>
		  			<div class="pure-u-1-4 pure-u-sm-1-6"><label>Count?</label></div>
		  			<div class="pure-u-1-2 pure-u-sm-1-3 hide-small"><label>Notes</label></div>
			<?php
			}
			
			$stmt = $conn->prepare("SELECT * FROM eq_pack_items
														WHERE pack_item_item_id = :pack_item_item_id AND pack_item_pack_id = :pack_item_pack_id");
			$stmt->bindValue(':pack_item_item_id', $item['item_id']);
			$stmt->bindValue(':pack_item_pack_id', $pack_id);
			$stmt->execute();
			$pack_item = $stmt->fetch(PDO::FETCH_ASSOC);
			?>
				<input type="hidden" name="pack_item_id[<?php print $count; ?>]" value="<?php if ($pack_item['pack_item_id'] != "") {print $pack_item['pack_item_id'];} else {print "0";} ?>">
	  			<input type="hidden" name="pack_item_item_id[<?php print $count; ?>]" value="<?php print $item['item_id']; ?>">
	  			<div class="pure-u-1-2 pure-u-sm-1-3">
	  				<input style="color: #222;" type=text class="pure-input-1" name="item_name" value="<?php print $item['item_name']; ?>" disabled>
	  			</div>	  			
	  			<div class="pure-u-1-4 pure-u-sm-1-6">
	  				<input style="text-align: center" type="text" class="pure-input-1" name="pack_item_num[<?php print $count; ?>]" value="<?php if ($pack_item['pack_item_num'] != "") {print $pack_item['pack_item_num'];} else {print "0";} ?>">
	  			</div>
	  			<div class="pure-u-1-4 pure-u-sm-1-6">
	  				<select class="pure-input-1" type="text" name="pack_item_dnc[<?php print $count; ?>]">
						<option value=""></option>
						<option value=0 <?php if($pack_item['pack_item_dnc'] == 0){print "selected";}?>>Yes</option>
						<option value=1 <?php if($pack_item['pack_item_dnc'] == 1){print "selected";}?>>No</option>
					</select>
	  			</div>
	  			<div class="pure-u-1 pure-u-sm-1-3 hide-small">
	  				<input type='text' class="pure-input-1" name="pack_item_note[<?php print $count; ?>]" value="<?php print $pack_item['pack_item_note']; ?>">
	  			</div>
			<?php 
			$count++;
			$item_type_old = $item_type_new;
		}
		?>
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
					table : "eq_packs",
					id_name : "pack_id",
					id: <?php print $pack_id; ?>
				},
				success : function() {
					$('.gl_input').delay(300)
				    .queue(function() {
				        $('.gl_input').css('background', '#FFF').dequeue();
				    });
				}
			});
		});
		
		$("#complete").click(function (e) {
			e.preventDefault();
			window.location.href = "index.php";
		});
		
		$("#print").click(function (e) {
			e.preventDefault();
			window.open("reports/r-pack-list.php?pid=<?php print $pack_id; ?>",'_blank');
		});
		
		$("#delete").click(function (e) {
			e.preventDefault();
			$.ajax({
				type : "POST",
				url : "process-eq-pack-delete.php",
				data : {
					id: <?php print $pack_id; ?>
				},
				success : function() {
					window.location.href = "index.php";
				}
			});
		});
		
		$(".pack-save").click(function () {
	    	$("#pack-list-form").submit();
	    	return false;
	  	});
		
		$("#pack-list-form").submit(function(e){
		   	e.preventDefault();
		 	$("#working").show();
		   	var senddata = $(this).serialize(); 	 	
		   	senddata = senddata.replace(/%5B/g, '[');
	  		senddata = senddata.replace(/%5D/g, ']');
	 	 	//alert (senddata);return false;
	 	 	
		    $.ajax({
		      type: "POST",
		      url: "process-pack-list.php",
		      data: senddata,
		      success: function() {
		      	window.location.href = "edit-pack.php?pid=<?php print $pack_id; ?>";
		      }           
		    }); 
		  });
		
	});

</script>