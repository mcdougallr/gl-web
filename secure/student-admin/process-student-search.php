<?php

	include ('../shared/dbconnect.php');
	
	$search_term = "%".strip_tags($_POST['searchterm'])."%";
	
	$stmt = $conn -> prepare("SELECT registration_id, student_name_common, student_name_last, accepted_session 
								FROM ss_registrations 
								WHERE student_name_last LIKE :student_name_last OR student_name_common LIKE :student_name_common 
								ORDER BY student_name_last, student_name_common");
	$stmt -> bindValue(':student_name_last', $search_term);
	$stmt -> bindValue(':student_name_common', $search_term);
	$stmt -> execute();
	$studentlist = $stmt->fetchAll();
	
	if ($studentlist != NULL) 
	{
		foreach ($studentlist as $student)
		{
			print "<li><a href=\"../student-edit/index.php?sid=".$student['registration_id']."#profile\" class=\"gl-menu-item student-m\">";
			print $student['student_name_last'].", ".$student['student_name_common'];
			if ($student['accepted_session'] != 0) {
				$stmt = $conn->prepare("SELECT * FROM ss_sessions WHERE session_id = :session_id");
				$stmt->bindValue(':session_id', $student['accepted_session']);
				$stmt->execute();
				$session = $stmt->fetch(PDO::FETCH_ASSOC);
				print "  (".$session['session_program_code'].$session['session_number'].")";
			}
			print "</a></li>";
		}
	}
	else {print "<li style=\"color: #FFF;font-weight: 300;\"><i class=\"fa fa-frown-o\"></i>No students meet that criteria...</li>";}
			    	
?>