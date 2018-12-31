<?php
if (isset($staff_id)) {$events = getstaffevents("V", $staff_id, $_SESSION['date'], $conn);}
else {$events = getevents("V", $_SESSION['date'], $conn);}

foreach ($events as $event)
{
	$stmt = $conn->prepare("SELECT * FROM schedule_visit  
							LEFT JOIN sy_schools ON sy_schools.school_id = schedule_visit.school_id
							LEFT JOIN sy_programs ON sy_programs.program_id = schedule_visit.program_id
							WHERE visit_id = :visit_id");
	$stmt->bindValue(':visit_id', $event['event_type_id']);
	$stmt->execute();
	$visit = $stmt->fetch(PDO::FETCH_ASSOC);
	?>
	<div id="v_edit_<?php print $event['event_id']; ?>" class="pure-g schedule-item">
		<div class="pure-u-1-12 icon"><i style="color: #093;" class="fa fa-tree"></i></div>
		<div class="pure-u-11-12">
			<div class="details">
				<div class="title">
					<?php 
					print $visit['program_name']; 
					if ($visit['early'] == "Y") {print " **";}
					?>
				</div>
				<?php 
				if ($visit['school_name'] !=""){print "<div class=\"info\">".$visit['school_name']."</div>";}
				if ($visit['teacher_name'] != "" OR $visit['grade'] != "" OR $visit['student_num'] != 0)
				{
					print "<div class=\"info\">(";
					if ($visit['teacher_name'] != ""){print $visit['teacher_name'];}
					if ($visit['grade'] != ""){print " - G. ".$visit['grade'];}
					if ($visit['student_num'] != 0){print " - #".$visit['student_num'];}
					print ")</div>"; 
				}
				if ($visit['visit_notes'] != ""){print "<div class=\"notes\">".$visit['visit_notes']."</div>";}
				
				$staffcount = 0;
				$staffresult = getstafflist($event['event_id'], $conn);
				$staffcount = count($staffresult);
				
				if ($staffcount > 0)
				{
					print "<div class=\"info\"><i style=\"color: #069;margin: 2px 5px 2px 0;\" class=\"fa fa-user\"></i>";
					$stafftally = 0;
					foreach ($staffresult as $staff)
					{
						if ($stafftally !=0) {print ", ";}
						print $staff['staff_name_common']." ".$staff['staff_name_last'];
						$stafftally++;
					}
					print "</div>";
				}
				?>
			</div>
		</div>
	</div>
	<?php
	$eventcount++;
}

?>