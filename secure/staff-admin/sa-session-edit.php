<?php
$page_title = "GL Session Edit";
include ("sa-header.php"); 

if (isset ($_GET['sid'])) {$session_id = cleantext($_GET['sid']);}
else {header('Location: index.php');}

$stmt = $conn->prepare("SELECT * FROM gl_staff_sessions WHERE staffing_session_id = :staffing_session_id");
$stmt->bindValue(':staffing_session_id', $session_id);
$stmt->execute();				
$session = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM gl_staff_session_days
											LEFT JOIN gl_staff_sessions ON gl_staff_sessions.staffing_session_id = gl_staff_session_days.sd_session_id
											WHERE sd_session_id = :sd_session_id 
											ORDER BY sd_start");
$stmt->bindValue(':sd_session_id', $session_id);
$stmt->execute();				
$sessiondates = $stmt->fetchAll();
?>

<link rel="stylesheet" href="_sa-session-edit.css">

<h1>Session Days for <?php print $session['staffing_session_program_code'] . $session['staffing_session_number'] . " (" . $session['staffing_session_portion'].")"; ?></h1>
<table class="session-table">
	<tr>
		<th style="padding: 0 5px;text-align: center;"><i class="fa fa-trash"></i></th>
		<th>Description</th>
		<th>Start Date</th>
		<th>End Date</th>		
		<th>%</th>
	</tr>
	<?php
	foreach ($sessiondates as $sessiondate)
	{?>
      	<tr>
      		<td style="padding: 0 5px;text-align: center;"><i id="d-<?php print $sessiondate['sd_id']; ?>" class="fa fa-trash del-session-days"></i></td>
      		<td>
      			<form class="pure-form pure-form-stacked gl-input-form">
		      		<input name="sd_id" type="hidden" value="<?php print $sessiondate['sd_id']; ?>"/>
					<input class="pure-input-1 gl-input" type=text name="sd_description" value="<?php print $sessiondate['sd_description']; ?>" />
				</form>
			</td>
			<td>
      			<form class="pure-form pure-form-stacked gl-input-form">
		      		<input name="sd_id" type="hidden" value="<?php print $sessiondate['sd_id']; ?>"/>
					<input class="pure-input-1 gl-input" type=date name="sd_start" value="<?php print date("Y-m-d", strtotime($sessiondate['sd_start'])); ?>" />
				</form>
			</td>
			<td>
      			<form class="pure-form pure-form-stacked gl-input-form">
		      		<input name="sd_id" type="hidden" value="<?php print $sessiondate['sd_id']; ?>"/>
					<input class="pure-input-1 gl-input" type=date name="sd_end" value="<?php print date("Y-m-d", strtotime($sessiondate['sd_end'])); ?>" />
				</form>
			</td>
			<td>
      			<form class="pure-form pure-form-stacked gl-input-form">
		      		<input name="sd_id" type="hidden" value="<?php print $sessiondate['sd_id']; ?>"/>
					<select class="pure-input-1 gl-input" name="sd_percentage">
		            <?php
								$options = array("1.0","0.5","0");
								foreach ($options as $option)
								{
									print "<option";
									if ($sessiondate['sd_percentage'] == $option) {print " selected";}
									print ">".$option."</option>";
								}
								?>
		          </select>		        
		        </form>
		    </td>
		</tr>
  	<?php 
	} ?>
</table>

<h1>Add Session Day</h1>
<form id="session-date-form-new" class="pure-form pure-form-stacked">
	<input name="sd_session_id" type="hidden" value="<?php print $session_id; ?>"/>
	<table class="session-table">
		<tr>
			<th>Description</th>
			<th>Start Date</th>
			<th>End Date</th>		
			<th>%</th>
		</tr>
		<tr>
			<td><input class="pure-input-1" type=text name="sd_description" /></td>
			<td><input class="pure-input-1" type=date name="sd_start" /></td>
			<td><input class="pure-input-1 " type=date name="sd_end" /></td>
			<td>
				<select class="pure-input-1" name="sd_percentage">
	            	<?php
					$options = array("1.0","0.5","0");
					foreach ($options as $option)
					{
						print "<option>".$option."</option>";
					}
					?>
		 		</select>
		 	</td>
		 </tr>
	</table>
	<div style="text-align: center;">
		<button id="dates_new_button" class="plaintext-button dates-button"><i class="fa fa-floppy-o"></i> save</button>
	</div>
</form>

<script>

	$(document).ready(function(){

		$(".del-session-days").click(function() {
			$("#working").show();
			id = $(this).attr("id");
			id = id.replace("d-", "");
			//alert(id);	
			$.ajax({
				type : "POST",
				url : "process-session-delete.php",
				data : {val : id},
				success : function() {location.reload('true');}
			});
		});

		$(".gl-input").change(function() {
			$(this).parents("form").submit();
    		return false;
		});
		
		$(".gl-input-form").submit(function(a) {
			a.preventDefault();	
			$("#working").show();
			var senddata = $(this).serialize();
	 	 	//alert (senddata);return false;
		    $.ajax({
		      type: "POST",
		      url: "process-session-update.php",
		      data: senddata,
		      success: function() {location.reload('true');}
			});
		});

		$("#dates_new_button").click(function() {
			$("#session-date-form-new").submit(function(a){	
				a.preventDefault();	
				$("#working").show();
		   		var senddata = $(this).serialize();
		 	 	//alert (senddata);return false;
			    $.ajax({
			      type: "POST",
			      url: "process-session-new.php",
			      data: senddata,
			      success: function() {location.reload('true');}
				});
			});
		});
		
	});

</script>
