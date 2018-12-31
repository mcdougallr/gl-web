<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$session = cleantext($_POST['session_id']);

$count = 1;
$stmt = $conn->prepare("SELECT waitlist_id, waitlist_student_id, registration_id, student_name_last, student_name_first FROM ss_waitlist
						LEFT JOIN ss_registrations ON ss_registrations.registration_id = ss_waitlist.waitlist_student_id
						WHERE waitlist_session_id = :waitlist_session_id
						ORDER BY waitlist_id");
$stmt->bindValue(':waitlist_session_id', $session);
$stmt->execute();
$students = $stmt->fetchAll();

foreach ($students as $student)
{?>
	<div class="student-list">
		<a href="../student-edit/index.php?sid=<?php print $student['registration_id']; ?>#course">
			<?php print $count.". ".$student['student_name_first']." ".$student['student_name_last']; ?>
		</a>
		<div style="display: inline;" class="<?php print $session; ?>">
			<i id="stu-<?php print $student['waitlist_id'];?>" class="fa fa-times del-student"></i>
		</div>
	</div>
	<?php
	$count++;
}
?>
