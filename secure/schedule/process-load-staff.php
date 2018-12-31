<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$event_id = cleantext($_POST['event_id']);
	
$stmt = $conn -> prepare("SELECT * FROM staff_workdays 
							LEFT JOIN staff ON staff.staff_id = staff_workdays.workday_staff_id
							WHERE workday_event_id = :workday_event_id
							ORDER BY staff_name_last");
$stmt -> bindValue(':workday_event_id', $event_id);
$stmt -> execute();
$stafflist = $stmt->fetchAll();

if ($stafflist != NULL) {
	foreach ($stafflist as $staff)
	{
		print $staff['staff_name_last'].", ".$staff['staff_name_common']." (".$staff['workday_percentage'].")";
		print "<i id=\"".$staff['workday_id']."\" class=\"fa fa-times delete_staff_workday\"></i><br />";
	}
}
else {print "Not staffed.";}

?>
<script>
   
	$(document).ready(function(){				
		
		// PROCESS FORM INPUT
		$('.delete_staff_workday').click(function(e) {
			$("#staff-info").fadeOut();
			$("#staff-updating").fadeIn();
			id = $(this).attr("id");
			//alert(name+" "+val);
			$.ajax({
				type : "POST",
				url : "process-staff-delete.php",
				data : {workday_id : id},
				success : function() {
					$('#staff-info').load('process-load-staff.php',{"event_id" : "<?php print $event_id; ?>"},function() {
						$("#staff-updating").fadeOut();
						$('#staff-info').fadeIn();
					});
				}
			});
		});
		
	});

</script>