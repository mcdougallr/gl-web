<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$trip_id = cleantext($_POST['trip_id']);
$tt = cleantext($_POST['tt']);

if ($tt == 0)
{
	$stmt = $conn -> prepare("SELECT * FROM fp_tripstaff
													LEFT JOIN staff ON staff.staff_id = fp_tripstaff.tripstaff_staff_id
													WHERE tripstaff_trip_id = :tripstaff_trip_id and tripstaff_tt = 0
													ORDER BY staff_name_last, staff_name_common");
}
else
{
	$stmt = $conn -> prepare("SELECT * FROM fp_tripstaff
													LEFT JOIN staff ON staff.staff_id = fp_tripstaff.tripstaff_staff_id
													WHERE tripstaff_trip_id = :tripstaff_trip_id and tripstaff_tt = 1
													ORDER BY staff_name_last, staff_name_common");
}

$stmt -> bindValue(':tripstaff_trip_id', $trip_id);
$stmt -> execute();
$stafflist = $stmt->fetchAll();

if ($stafflist != NULL) {
	foreach ($stafflist as $staff)
	{
		print "<div id=\"tripstaff_".$staff['tripstaff_id']."\" class=\"\">";
		print $staff['staff_name_last'].", ".$staff['staff_name_common'];
		print "<i id=\"".$staff['tripstaff_id']."\" class=\"fa fa-times delete-tripstaff\"></i></div>";
	}
}
else
{
	if ($tt == 0){print "<div><i class=\"fa fa-angle-double-left\"></i> No staff <i class=\"fa fa-angle-double-right\"></i></div>";}
	else {print "<div><i class=\"fa fa-angle-double-left\"></i> No twin trip staff <i class=\"fa fa-angle-double-right\"></i></div>";}
}
?>
<script>
   
	$(document).ready(function(){				
		
		// PROCESS FORM INPUT
		$('.delete-tripstaff').click(function(e) {
			id = $(this).attr("id");
			del_id = "#tripstaff_" + id;
			//alert(id+" "+del_id);
			$.ajax({
				type : "POST",
				url : "process-tripstaff-delete.php",
				data : {tripstaff_id : id},
				success : function() {
					$('#staff-info-<?php print $tt; ?>').load('process-tripstaff-load.php',{"trip_id" : "<?php print $trip_id; ?>","tt": "<?php print $tt; ?>"},function() {
						$('#staff-info-<?php print $tt; ?>').fadeIn('fast');
					});
				}
			});
		});
		
	});

</script>