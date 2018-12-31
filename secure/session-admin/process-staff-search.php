<?php

	include ('../shared/dbconnect.php');
	
	$search_term = "%".strip_tags($_POST['searchterm'])."%";
	
	$stmt = $conn -> prepare("SELECT staff_id, staff_name_last, staff_name_common, admin_archive  FROM staff 
							WHERE staff_name_last LIKE :staff_name_last OR staff_name_common LIKE :staff_name_common 
							ORDER BY admin_archive, staff_name_last, staff_name_common");
	$stmt -> bindValue(':staff_name_last', $search_term);
	$stmt -> bindValue(':staff_name_common', $search_term);
	$stmt -> execute();
	$stafflist = $stmt->fetchAll();
	
	if ($stafflist != NULL) 
	{
		foreach ($stafflist as $staff)
		{
			print "<li";
			if ($staff['admin_archive'] == "Yes") {print " style=\"background: #999;\"";}
			print "><a href=\"../staff-edit/index.php?sid=".$staff['staff_id']."#profile\" class=\"gl-menu-item staff-m\">";
			print $staff['staff_name_last'].", ".$staff['staff_name_common'];
			print "</a></li>";
		}
	}
	else {print "<li style=\"color: #FFF;font-weight: 300;\"><i class=\"fa fa-frown-o\"></i>No staff meet that criteria...</li>";}
			    	
?>