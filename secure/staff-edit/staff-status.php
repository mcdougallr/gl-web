<?php 
	session_start();
	include ('../shared/dbconnect.php');
  	include ('../shared/clean.php');
	
	$staff = cleantext($_POST['staff']);

	$stmt = $conn -> prepare("SELECT admin_summer, admin_summer_confirmed, admin_summer_display, admin_archive FROM staff
												WHERE staff_id = :staff_id");
	$stmt -> bindValue(':staff_id', $staff);
	$stmt -> execute();
	$staffstatus = $stmt -> fetch(PDO::FETCH_ASSOC);	
	
	if ($staffstatus['admin_archive'] == "Yes")
	{
		print "Archived...";
	}
	else {
		if ($staffstatus['admin_summer_display'] == 1){print "<i class=\"fa fa-eye\"></i>";}
		else
		{
			if ($staffstatus['admin_summer_confirmed'] == 0) {print "<i class=\"fa fa-times\"></i>";} 
			else {print "<i class=\"fa fa-check\"></i>";}
		}		
		if ($staffstatus['admin_summer'] != ""){print " ".$staffstatus['admin_summer'];}
		else {print " -";}
	}
		
?>