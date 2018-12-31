<?php
	$page_title = "GL Student Med Pick";
	include ("sta-header.php"); 

	if (isset($_POST['sid'])) {$session_id = cleantext($_POST['sid']);}
	else {$session_id = "";}
	
	$session_program_code = "";
	
	if ($session_id != "" and $session_id == "all")
	{
		$stmt = $conn->prepare("SELECT * FROM ss_registrations
								WHERE accepted_session != 0
								ORDER BY student_name_last, student_name_first");
		$stmt->bindValue(':accepted_session', $session_id);
		$stmt->execute();						
		$student_result = $stmt->fetchAll();
		
		$session_program_code = "All Students";
		$session_id = "";
	}
	else 
	{
		$stmt = $conn->prepare("SELECT * FROM ss_registrations
															WHERE accepted_session = :accepted_session 
															ORDER BY student_name_last, student_name_first");
		$stmt->bindValue(':accepted_session', $session_id);
		$stmt->execute();						
		$student_result = $stmt->fetchAll();
		
		$stmt = $conn->prepare("SELECT * FROM ss_sessions WHERE session_id = :session_id");
		$stmt->bindValue(':session_id', $session_id);
		$stmt->execute();
		$session_result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$session_program_code = $session_result['session_program_code'];
		
		$session_id = $session_result['session_number'];
	}	
				
	$student_count = count($session_result)/3-1;			
	
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
		color: #FF8033;
		font-variant: small-caps;
	}
</style>

<h1>Print Med Forms - <?php print $session_program_code.$session_id; ?></h1>

<div class="pure-g" style="padding: 10px;">
	<form style="width: 100%;" id="med-print" class="pure-form pure-form-stacked" method="POST" action="reports/medform.php" target="_new">
		<?php						
		$i = 0;
		$j = 0;
		?>
		<div class="pure-u-1">
			<input type="checkbox" class="checkall"><label><em>Select All</em></label>
			<hr>
		</div>
		<div class="pure-u-1-3" style="text-align: left;">
			<?php
			foreach ($student_result as $student)
			  {?>
					<input type="hidden" name=registration_id[<?php print $i; ?>] value="<?php print $student['registration_id']; ?>">
					<input type="checkbox" class="tocheck" name=printchecked[<?php print $i; ?>] value=1>
					<label>
						<?php 
						print $student['student_name_last'].", ".$student['student_name_first']; 
						if ($student['admin_flag_notes'] != "") {print "*";}
						print " ";
						if ($student['confirm_photo'] == "N") {print "<i class=\"fa fa-camera\"></i>";}
						if ($student['confirm_social_media'] == "N") {print "<i class=\"fa fa-twitter\"></i>";}
						?>
					</label>
					<br />
					<?php
					$i++;
					$j++;
					if ($j > $student_count) {print "</div><div class=\"pure-u-1-3\" style=\"text-align: left;\">"; $j = 0;}
				} 
			?>
		</div>			
		<div class="pure-u-1" style="text-align: center;font-size: .9em;">				
			<button type="submit" class="plaintext-button"><i class="fa fa-print"></i> print med forms</button>
		</div>
	</form>
</div>
		
<?php include("sta-footer.php"); ?>

<script>

$(document).ready(function(){
	$('.checkall').change(function() {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
	});
});

</script>