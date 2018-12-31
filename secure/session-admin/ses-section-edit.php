<?php
$page_title = "GL Section Edit";
include ("ses-header.php"); 
include ('../shared/clean.php');

if (isset ($_GET['sid'])) {$section_id = cleantext($_GET['sid']);}
else {header('Location: index.php');}

$stmt = $conn->prepare("SELECT * FROM ss_session_sections 
						LEFT JOIN ss_sessions ON ss_session_sections.section_session_id = ss_sessions.session_id
						WHERE section_id = :section_id");
$stmt->bindValue(':section_id', $section_id);
$stmt->execute();				
$section = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM schedule_summer
						LEFT JOIN schedule_events ON schedule_events.event_type_id = schedule_summer.summer_id
						LEFT JOIN ss_session_sections ON ss_session_sections.section_id = schedule_summer.summer_section_id
						WHERE summer_section_id = :summer_section_id and event_type = 'X'
						ORDER BY event_date");
$stmt->bindValue(':summer_section_id', $section_id);
$stmt->execute();				
$events = $stmt->fetchAll();
?>

<link rel="stylesheet" href="_ses-section-edit.css">

<h1>Section Days for <?php print $section['session_program_code'] . $section['session_number'] . " (" . $section['section_name'].")"; ?></h1>
<form class="pure-form pure-form-stacked gl-input-form">
	<table class="session-table">
		<tr>
			<th style="padding: 0 5px;text-align: center;"><i class="fa fa-trash"></i></th>
			<th>Description</th>
			<th>Date</th>		
			<th>%</th>
			<th>Notes</th>
		</tr>
		<?php
		foreach ($events as $event)
		{?>
	      	<tr id="tr<?php print $event['event_id']; ?>">
	      		<td style="padding: 0 5px;text-align: center;"><i id="d-<?php print $event['event_id']; ?>" class="fa fa-trash del-section-day"></i></td>
	      		<td><input id="des-<?php print $event['summer_id']; ?>" class="pure-input-1 gl-input" type=text name="summer_description" value="<?php print $event['summer_description']; ?>" /></td>
				<td><input id="dat-<?php print $event['event_id']; ?>" class="pure-input-1 gl-input-date" type=date name="event_date" value="<?php print date("Y-m-d", strtotime($event['event_date'])); ?>" /></td>
				<td>
					<select id="per-<?php print $event['summer_id']; ?>" class="pure-input-1 gl-input" name="summer_percentage">
		            <?php
						$options = array("1.0","0.5","0");
						foreach ($options as $option)
						{
							print "<option";
							if ($event['summer_percentage'] == $option) {print " selected";}
							print ">".$option."</option>";
						}
					?>
		          	</select>	
		    	</td>
		    	<td><input id="not-<?php print $event['summer_id']; ?>" class="pure-input-1 gl-input" type=text name="summer_notes" value="<?php print $event['summer_notes']; ?>" /></td>
			</tr>
	  	<?php 
		} ?>
	</table>
</form>

<h1>Add Section Day</h1>
<form method="POST" action="process-section-date-new.php" id="session-date-form-new" class="pure-form pure-form-stacked" >
	<input name="summer_section_id" type="hidden" value="<?php print $section['section_id']; ?>"/>
	<table class="session-table">
		<tr>
			<th style="padding: 0 5px;text-align: center;"><i class="fa fa-floppy-o"></i></th>
			<th>Description</th>
			<th>Date</th>		
			<th>%</th>
			<th>Notes</th>
		</tr>
		<tr>
	      	<td style="padding: 0 5px;text-align: center;"><button type="submit" id="dates_new_button" class="plaintext-button dates-button"><i class="fa fa-floppy-o"></i></button></td>
			<td><input class="pure-input-1" type=text name="summer_description" /></td>
			<td><input class="pure-input-1" type=date name="event_date" /></td>
			<td>
				<select class="pure-input-1" name="summer_percentage">
	            	<?php
					$options = array("1.0","0.5","0");
					foreach ($options as $option)
					{
						print "<option>".$option."</option>";
					}
					?>
		 		</select>
		 	</td>
		 	<td><input class="pure-input-1 " type=text name="summer_notes" /></td>
		 </tr>
	</table>
</form>

<script>

	$(document).ready(function(){

		// PROCESS FORM INPUT
		$('.gl-input').change(function(e) {
			summer_id = $(this).attr("id").substring(4);
			field_name = $(this).attr("name");
			field_val = $(this).val();
			$(this).css('background', '#685191');
			//alert(field_name+" "+field_val+" "+summer_id);
			$.ajax({
				type : "POST",
				url : "process-section-date-input.php",
				data : {
					field_name : field_name,
					field_val : field_val,
					summer_id : summer_id
				},
				success : function() {
					$('.gl-input').delay(300)
				    .queue(function() {
				        $('.gl-input').css('background', 'transparent').dequeue();
				    });
				}
			});
		});
		
		$('.gl-input-date').change(function(e) {
			event_id = $(this).attr("id").substring(4);
			field_val = $(this).val();
			$(this).css('background', '#685191');
			//alert(field_val+"-"+event_id);
			$.ajax({
				type : "POST",
				url : "process-section-date-input-date.php",
				data : {
					field_val : field_val,
					event_id: event_id
				},
				success : function() {
					$('.gl-input-date').delay(300)
				    .queue(function() {
				        $('.gl-input-date').css('background', 'transparent').dequeue();
				    });
				}
			});
		});

		$(".del-section-day").click(function() {
			if (confirm("Delete? Are you sure? All staff workday associated with this day will also be deleted!") == true)
			{
				$("#working").show();
				id = $(this).attr("id");
				id = id.replace("d-", "");
				tid = "#tr"+id;
				//alert(id+" "+tid);	
				$.ajax({
					type : "POST",
					url : "process-section-date-delete.php",
					data : {event_id : id},
					success : function() {$("#working").hide();$(tid).hide();}
				});
			}
		});
		
	});

</script>
