<?php
$page_title = "GL Session Edit";
include ("ses-header.php"); 
include ('../shared/clean.php');

if (isset ($_GET['sid'])) {$session_id = cleantext($_GET['sid']);}
else {header('Location: index.php');}

$stmt = $conn->prepare("SELECT * FROM ss_sessions WHERE session_id = :session_id");
$stmt->bindValue(':session_id', $session_id);
$stmt->execute();
$session = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<link rel="stylesheet" href="_ses-session-edit.css">

<div class="pure-g">
	<div class="pure-u-1 pure-u-md-1-2">
		<h2>Edit <?php print $session['session_program_code'].$session['session_number']; ?> Details</h2>
		<form id="gen-day-form" class="pure-form pure-form-stacked">
			<div class="pure-g">
				<div class="pure-u-1 pure-u-sm-1-2">
					<div class="input-padding">
						<label>Start Date</label>
						<input class="pure-input-1 gl-input" type="date" name="session_start" onkeypress="return noenter()" value="<?php print $session['session_start']; ?>">
					</div>
				</div>
				<div class="pure-u-1 pure-u-sm-1-2">
					<div class="input-padding">
						<label>End Date</label>
						<input class="pure-input-1 gl-input" type="date" name="session_end" onkeypress="return noenter()" value="<?php print $session['session_end']; ?>">
					</div>
				</div>
				<div class="pure-u-1 pure-u-sm-1-3">
					<div class="input-padding">
						<label>Capacity</label>
						<input class="pure-input-1 gl-input" type="text" name="session_capacity" value="<?php print $session['session_capacity']; ?>">
					</div>
				</div>
				<div class="pure-u-1 pure-u-sm-1-3">
					<div class="input-padding">
						<label>Visible?</label>
		                <select style="-webkit-appearance: none;" class="pure-input-1 gl-input" onkeypress="return noenter()" name="session_visible">
	                  		<option value="1" <?php if ($session['session_visible'] == "1"){print " selected";} ?>>Yes</option>
	                  		<option value="0" <?php if ($session['session_visible'] == "0"){print " selected";} ?>>No</option>
	                	</select>
	                </div>
				</div>
				<div class="pure-u-1 pure-u-sm-1-3">
					<div class="input-padding">
						<label>Sort Order</label>
						<input class="pure-input-1 gl-input" type="text" name="session_sortorder" value="<?php print $session['session_sortorder']; ?>">
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="pure-u-1 pure-u-md-1-2">		
		<h2><?php print $session['session_program_code'].$session['session_number']; ?> Float Plans</h2>
		<h2><?php print $session['session_program_code'].$session['session_number']; ?> Admin Debriefs</h2>
		<h2><?php print $session['session_program_code'].$session['session_number']; ?> Debrief Notes</h2>		
	</div>	
</div>

<script>
   
	$(document).ready(function(){				
	
		// PROCESS FORM INPUT
		$('.gl-input').change(function(e) {
			field_name = $(this).attr("name");
			field_val = $(this).val();
			$(this).css('background', '#685191');
			//alert(field_name+" "+field_val);
			$.ajax({
				type : "POST",
				url : "process-session-input.php",
				data : {
					field_name : field_name,
					field_val : field_val,
					session_id : <?php print $session_id; ?>
				},
				success : function() {
					$('.gl-input').delay(300)
				    .queue(function() {
				        $('.gl-input').css('background', 'transparent').dequeue();
				    });
				}
			});
		});
				
	});

</script>