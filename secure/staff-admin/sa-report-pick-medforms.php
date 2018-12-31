<?php
	$page_title = "GL Med Forms";
	include ("sa-header.php"); 
?>

<style>
	#med-print label {
		display: inline;
		font-size: .9em;
		margin: 2px 5px;
	}
	.plaintext-button {
		background: transparent;
		border: none;
		font-size: 1.3em;
		color: #6F9FC4;
		font-variant: small-caps;
	}
</style>

<h1>Print Staff Med Forms</h1>

<div class="pure-g" style="padding: 10px;">
	<form style="width: 100%;" id="med-print" class="pure-form pure-form-stacked" method="POST" action="reports/r-medform.php" target="_new">
		<div class="pure-u-1">
			<input type="checkbox" class="checkall"><label><em>Select All</em></label>
			<hr>
		</div>
		<div class="pure-u-1-3" style="text-align: left;">
			<?php
			$i = 0;
			$j = 0;
			$stmt = $conn->prepare("SELECT * FROM staff WHERE admin_archive = 'No' ORDER BY staff_name_last, staff_name_common");
			$stmt->execute();
			$stafflist= $stmt->fetchAll();
						
			$staff_count = count($stafflist)/3-1;	
			foreach ($stafflist as $staff)
		  	{?>
				<input type="hidden" name="staff_id[<?php print $i; ?>]" value="<?php print $staff['staff_id']; ?>">
				<input type="checkbox" class="tocheck" name=printchecked[<?php print $i; ?>] value=1>
				<label>
					<?php print $staff['staff_name_last'].", ".$staff['staff_name_common'];?>
				</label>
				<br />
				<?php
				$i++;
				$j++;
				if ($j > $staff_count) {print "</div><div class=\"pure-u-1-3\" style=\"text-align: left;\">"; $j = 0;}
			}?>
		</div>			
		<div class="pure-u-1" style="text-align: center;font-size: .9em;">				
			<button type="submit" class="plaintext-button"><i class="fa fa-print"></i> print med forms</button>
		</div>
	</form>
</div>
	
<?php include("sa-footer.php"); ?>

<script>
	$(document).ready(function(){
		$('.checkall').change(function() {
	    $("input:checkbox").prop('checked', $(this).prop("checked"));
		});
	});
</script>