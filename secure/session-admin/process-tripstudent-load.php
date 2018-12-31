<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$trip_id = cleantext($_POST['trip_id']);
$extra = cleantext($_POST['extra']);

if ($extra == 0)
{
	$stmt = $conn -> prepare("SELECT * FROM fp_tripstudents
													LEFT JOIN ss_registrations ON  ss_registrations.registration_id = fp_tripstudents.tripstudent_student_id
													WHERE tripstudent_trip_id = :tripstudent_trip_id and tripstudent_extra = 0
													ORDER BY student_name_last, student_name_common");
}
else
{
	$stmt = $conn -> prepare("SELECT * FROM fp_tripstudents
													LEFT JOIN ss_registrations ON  ss_registrations.registration_id = fp_tripstudents.tripstudent_student_id
													WHERE tripstudent_trip_id = :tripstudent_trip_id and tripstudent_extra = 1
													ORDER BY student_name_last, student_name_common");
}

$stmt -> bindValue(':tripstudent_trip_id', $trip_id);
$stmt -> execute();
$studentlist = $stmt->fetchAll();

if ($studentlist != NULL) {
	foreach ($studentlist as $student)
	{
		print "<div id=\"tripstudent_".$student['tripstudent_id']."\" class=\"\">";
		print $student['student_name_last'].", ".$student['student_name_common'];
		print "<i id=\"".$student['tripstudent_id']."\" class=\"fa fa-times delete-tripstudent\"></i></div>";
	}
}
else
{
	if ($extra == 0){print "<div><i class=\"fa fa-angle-double-left\"></i> No students <i class=\"fa fa-angle-double-right\"></i></div>";}
	else {print "<div><i class=\"fa fa-angle-double-left\"></i> No WICs or volunteers <i class=\"fa fa-angle-double-right\"></i></div>";}
}

?>
<script>
   
	$(document).ready(function(){				
		
		// PROCESS FORM INPUT
		$('.delete-tripstudent').click(function(e) {
			id = $(this).attr("id");
			del_id = "#tripstudent_" + id;
			//alert(id+" "+del_id);
			$.ajax({
				type : "POST",
				url : "process-tripstudent-delete.php",
				data : {tripstudent_id : id},
				success : function() {
					$('#student-info-<?php print $extra; ?>').load('process-tripstudent-load.php',{"trip_id" : "<?php print $trip_id; ?>","extra": "<?php print $extra; ?>"},function() {
						$('#student-info-<?php print $extra; ?>').fadeIn('fast');
					});
				}
			});
		});
		
	});

</script>