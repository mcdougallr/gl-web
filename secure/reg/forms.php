<?php
session_start();
$db = "outed";
?>

<html>
<head>
<link href="regcssprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gould Lake Registration 2019</title>

<?php

include ('../shared/dbconnect.php');
include ('../shared/functions.php');

if (isset($_GET['rcd']) and isset($_GET['rid']))
{
	$registration_id = $_GET['rid'];
	$registration_code = $_GET['rcd'];

$stmt = $conn->prepare("SELECT * FROM ss_registrations
						LEFT JOIN ss_programs ON ss_registrations.selected_program = ss_programs.program_id
						WHERE registration_id = :reg_id and registration_code = :reg_code");
		$stmt -> bindValue(':reg_id', $registration_id);
		$stmt -> bindValue(':reg_code', $registration_code);
		$stmt-> execute();
		$data = $stmt->fetch(PDO::FETCH_ASSOC);

}

$stmt = $conn->prepare("SELECT * FROM ss_prefs");
$stmt->execute();
$prefs = $stmt->fetch(PDO::FETCH_ASSOC);

?>
</head>

<body>
<div class="pagebreak">
	<div id="gl-title">Gould Lake Outdoor Centre <img id="gl-sunman" src="sunnyblack.png" width="61" height="55" alt="sunman.png"></div>
	<h3>Student Registration Package 2019</h3>
	<h4 style="font-size:.8em;">Page 1 of 3</h4>
	<h4>Please complete the following package and mail it to the Gould Lake Outdoor Centre to complete the registration process.</h4>
	<h4 style="font-weight:bold;font-size:1em;">Gould Lake Outdoor Centre <br />
			2857 Rutledge Rd.<br />
			Sydenham, ON<br />
			K0H 2T0</h4>

	<hr />

	<h1><?php print $data['student_name_last']. ", " .$data['student_name_first']; ?></h1>
	<h6>Reference Information for Gould Lake Administration</h6>
	<div class="form-wrapper">			
		<h5><em>Reference Number:</em> <?php print $data['registration_code']." - ".$data['registration_id']; ?></h5>
		<h5><em>Contact Email:</em> <?php print $data['contact_email']; ?></h5>
		<h5><em>OEN:</em> <?php print $data['student_oen']; ?></h5>
		<h5><em>DOB:</em> <?php print $data['student_dob']; ?></h5>
		<h5><em>Sex:</em> <?php print $data['student_sex']; ?></h5>		
	</div>
		
	<h6>Primary Contact</h6>
	<div class="form-wrapper">
		<h5><em>Name:</em> <?php print $data['g1_name_first']." ".$data['g1_name_last']; ?></h5>
		<h5><em>Phone 1:</em> <?php print $data['g1_p1']." (".$data['g1_p1_type'].")"; ?></h5>
		<h5><em>Phone 2:</em> <?php print $data['g1_p2']." (".$data['g1_p2_type'].")"; ?></h5>
		<h5><em>Address:</em> <?php print $data['g1_address'].", ".$data['g1_city']." ".$data['g1_province'].", ".$data['g1_postalcode']; ?></h5>
	</div>

	<h6>Course Information</h6>
	<div class="form-wrapper">	
		<h5><em>Course:</em> <?php print $data['program_name'];?></h5>
		<h5><em>Session Choice 1:</em> 
		<?php
		//GET INFO ON SESSION CHOICE 1
		$stmt = $conn->prepare("SELECT * FROM ss_sessions
														WHERE session_id = :session_id");
		$stmt -> bindValue(':session_id', $data['selected_session1']);
		$stmt-> execute();
		$session = $stmt->fetch(PDO::FETCH_ASSOC);
		
		print $session['session_program_code']. " ". $session['session_number'];?></h5>
		
		<?php
			if ($data['selected_session2'] !=0 )
			{
				print "<h5><em>Session Choice 2:</em> ";
				//GET INFO ON SESSION CHOICE 2
					$stmt = $conn->prepare("SELECT * FROM ss_sessions
																	WHERE session_id = :session_id");
					$stmt -> bindValue(':session_id', $data['selected_session2']);
					$stmt-> execute();
					$session = $stmt->fetch(PDO::FETCH_ASSOC);
			
				print $session['session_program_code']. " ". $session['session_number']."</h5>";
			}?>
		</div>
</div>

<div class="pagebreak">
	<div id="gl-title">Gould Lake Outdoor Centre <img id="gl-sunman" src="sunnyblack.png" width="61" height="55" alt="sunman.png"></div>
	<h3>Student Registration Package 2019</h3>
	<h4 style="font-size:.8em;">Page 2 of 3</h4>
	
	<h3><?php print $data['student_name_first']. " " .$data['student_name_last']; ?></h3>
	<h3><?php print $data['program_name']; ?></h3>
	
	<table width=700 border=0 cellpadding="0" cellspacing="0" align=center>
	
	<?php 
	
	for ($i=1; $i<4; $i++)
	{
	
	print "<tr><td width=600><br>";
	
	print "<table width=600 border=1 cellspacing=0 cellpadding=10 align=center>";
	print "<tr height=150><td valign=center class=\"verdana12\" align=center><b>Affix Cheque ".$i." Here</b>";
	$field = "payment".$i."_date";
	print "<br />Cheque dated for <b>" .date ("F j, Y",strtotime($prefs[$field]));
	
	print "</b><br />Please make cheques payable to <b>Limestone District School Board</b><br><br>";
	
	print $data['program_name'];
	print " Payment ".$i." Amount: <b>$";
	$field = "program_payment".$i;
	print $data[$field];
	
	print "</b></td></tr></table>";
	
	print "</td></tr>";
	
	}
	
	?>
	</table>
	
	<h4>All prices apply to permanent residents of Ontario only.<br />
	For students who reside outside of Ontario, please contact our office for program fee structure.<h4>


</div>

<div class="pagebreak">
	<div id="gl-title">Gould Lake Outdoor Centre <img id="gl-sunman" src="sunnyblack.png" width="61" height="55" alt="sunman.png"></div>
	<h3>Risk Waiver Form 2019</h3>
	<h4 style="font-size:.8em;">Page 3 of 3</h4>

	<p align="center">This form <u>must</u> be signed and attached to the application</p>
	
	<p>
	In consideration of the Gould Lake Outdoor Centre accepting the registration for <b><?php print $data['student_name_first']. " " .$data['student_name_last']; ?></b> in the Gould Lake Summer Programs, I the parent/guardian:</p>
	
	<ul>
	<li>Understand that there is a risk element in the course, and that I have been informed as such by the Gould Lake Outdoor Centre. I accept such risks as being part of the nature of this program.</li>
	
	<li>Certify that my child is in good health. He/she has not recently been treated for, nor am I aware of, any condition that would jeopardize his/her health, or prevent his/her full participation in this course.</li>
	
	<li>Agree that my child must abide by the rules and regulations of the course, its director and instructors, and the Limestone District School Board in all matters. Ultimately, it is my child's responsibility to know the possible consequences of their actions, and personally assume the consequences of their actions. In the event of the expulsion of my child from a course due to the use of drugs, alcohol, violent behavior or any other violation of board policy, I understand that as the parent/guardian of the child, I will bear the full cost of the evacuation and forfeit the course tuition.</li>
	
	<li>Agree that in the case of an emergency, Gould Lake staff, Limestone District School Board agents or medical officials have my permission to authorize appropriate medical care for my child. </li>
	
	<li>Understand that, in the case of an emergency/medical evacuation, the Gould Lake staff will take full responsibility to ensure that the student is treated and taken to a medical emergency facility, however I the parent/guardian will bear any costs associated with medical treatment, transportation or other costs related to the evacuation.</li>
	
	</ul>
	
	<p>I have read the <a href="http://outed.limestone.on.ca/regfiles/regpolicieslet19.pdf" target="_blank">Registration Policies Letter 2019</a> and:</p>
	
	<ol>
	<li>I understand and agree to the terms of the refund policy.</li>
	<li>I understand the program philosophy in regards to creating balanced tripping groups. </li>
	<li>I understand the need for program prerequisites.</li>
	</ol>
	<p>Having read and understood the terms of this document, I hereby release and forever discharge the Gould Lake Outdoor Centre, its director, instructors and employees, and their successors and heirs and assigns, from any liability or claim for damages for loss of any nature including delays, personal injury or loss of personal property, how-so-ever caused, whether by negligence, act of God, equipment failure, or any act of nature incurred during, or as a result of my child's participation in this course, and I declare that this waiver is binding upon myself, my heirs, executors, administrators and assigns.</p>
	
	<p>I hereby grant permission for my child <?php print $data['student_name_first']. " " .$data['student_name_last']; ?> to participate in the aforementioned program.</p>
	
	<br />
	<table align=center border=0 cellpadding=0 cellspacing=0>
	<tr><td width=300 align=center>_____________________________________________<br>Signature of Parent/Guardian or Student (if over 18)
	</td><td width = 300 align=center>_____________________________________________<br>Date</td></tr></table>
</div>
</body>
</html>
