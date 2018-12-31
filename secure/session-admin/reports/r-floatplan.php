<?php
session_start();

include ('../../shared/dbconnect.php');
include ('../../shared/clean.php');
include ('../../shared/authenticate.php');

if (isset ($_GET['sid'])) {$section_id = cleantext($_GET['sid']);}
else {header('Location: index.php');}

if (isset ($_GET['tid'])) {$trip_id = cleantext($_GET['tid']);}
else {$trip_id = 0;}

$stmt = $conn->prepare("SELECT * FROM ss_session_sections 
						LEFT JOIN ss_sessions ON ss_session_sections.section_session_id = ss_sessions.session_id
						WHERE section_id = :section_id");
$stmt->bindValue(':section_id', $section_id);
$stmt->execute();				
$section = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<html>
	<head>
		<link href="reportprint.css" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php print $section['session_program_code'].$section['session_number']; ?> Float Plan</title>
	</head>
	<body>
		<h1><?php print $section['session_program_code'].$section['session_number']; ?> Float Plan</h1>
		<?php
			$first = 0;
			if ($trip_id == 0) 
			{
				$stmt = $conn->prepare("SELECT * FROM fp_trips WHERE trip_section_id = :trip_section_id ORDER BY trip_id ASC");
				$stmt->bindValue(':trip_section_id', $section_id);
				$stmt->execute();				
				$trips = $stmt->fetchAll();
			}
			else
			{
				$stmt = $conn->prepare("SELECT * FROM fp_trips WHERE trip_id = :trip_id");
				$stmt->bindValue(':trip_id', $trip_id);
				$stmt->execute();				
				$trips = $stmt->fetchAll();
			}
			foreach ($trips as $trip)
			{
				$first++;
				$id = $trip['trip_id'];
				$stmt = $conn -> prepare("SELECT staff_name_last, staff_name_common FROM fp_tripstaff
														LEFT JOIN staff ON staff.staff_id = fp_tripstaff.tripstaff_staff_id
														WHERE tripstaff_trip_id = '$id'  AND tripstaff_tt = 0
														ORDER BY staff_name_last");
				$stmt -> execute();
				$stafflist = $stmt -> fetchAll();

				$stmt = $conn -> prepare("SELECT staff_name_last, staff_name_common FROM fp_tripstaff
														LEFT JOIN staff ON staff.staff_id = fp_tripstaff.tripstaff_staff_id
														WHERE tripstaff_trip_id = '$id'  AND tripstaff_tt = 1
														ORDER BY staff_name_last");
				$stmt -> execute();
				$ttstafflist = $stmt -> fetchAll();

				$stmt = $conn -> prepare("SELECT student_name_last, student_name_common FROM fp_tripstudents
														LEFT JOIN ss_registrations ON ss_registrations.registration_id = fp_tripstudents.tripstudent_student_id
														WHERE tripstudent_trip_id = '$id'  AND tripstudent_extra = 0
														ORDER BY student_name_last, student_name_common");
				$stmt -> execute();
				$studentlist = $stmt -> fetchAll();

				$stmt = $conn -> prepare("SELECT student_name_last, student_name_common FROM fp_tripstudents
														LEFT JOIN ss_registrations ON ss_registrations.registration_id = fp_tripstudents.tripstudent_student_id
														WHERE tripstudent_trip_id = '$id'  AND tripstudent_extra = 1
														ORDER BY student_name_last, student_name_common");
				$stmt -> execute();
				$xstudentlist = $stmt -> fetchAll();

				$sat_id = $trip['trip_sat'];
				$stmt = $conn -> prepare("SELECT * FROM fp_sats WHERE sat_id = '$sat_id' ");
				$stmt -> execute();
				$sat = $stmt->fetch(PDO::FETCH_ASSOC);

				$ttsat_id = $trip['trip_sat_tt'];
				$stmt = $conn -> prepare("SELECT * FROM fp_sats WHERE sat_id = '$ttsat_id' ");
				$stmt -> execute();
				$ttsat = $stmt->fetch(PDO::FETCH_ASSOC);
				?>
				<table align=center width="100%" style="page-break-inside: avoid;">
					<tr><td align=center colspan=4 style="background: #222;color: #FFF;"><strong><?php print $trip['trip_name']; ?></strong></td></tr>
					<tr>
						<td style="vertical-align: top;">
							<p>
								<strong>Staff</strong>
								<?php foreach ($stafflist as $staff) {print "<br />".$staff['staff_name_last'].", ".$staff['staff_name_common'];} ?>
							</p>
							<p>
								<strong>Sat Phone #</strong>
								<?php print "<br />".$sat['sat_name']." - ".$sat['sat_num']; ?>
							</p>
							<hr />
							<p>
								<strong>Twin Trip Staff</strong>
								<?php foreach ($ttstafflist as $staff) {print "<br />".$staff['staff_name_last'].", ".$staff['staff_name_common'];} ?>
							</p>
							<p>
								<strong>Twin Trip Sat Phone #</strong>
								<?php print "<br />".$ttsat['sat_name']." - ".$ttsat['sat_num']; ?>
							</p>
						</td>
						<td style="vertical-align: top;">
							<p>
								<strong>Students</strong>
								<?php foreach ($studentlist as $student) {print "<br />".$student['student_name_last'].", ".$student['student_name_common'];} ?>
							</p>
							<hr />
							<p>
								<strong>WICs/Volunteers</strong>
								<?php foreach ($xstudentlist as $student) {print "<br />".$student['student_name_last'].", ".$student['student_name_common'];} ?>
							</p>
						</td>
						<td style="vertical-align: top;">
							<p>
								<strong>Trip Drop-Off</strong>
								<?php print "<br />".$trip['trip_d_loc']; ?>
							</p>
							<p>
								<strong>Trip Pickup</strong>
								<?php print "<br />".$trip['trip_p_loc']; ?>
							</p>
							<p>
								<strong>Trip Pickup Time</strong>
								<?php print "<br />".$trip['trip_p_time']; ?>
							</p>
							<p>
								<strong>Tents # / Colour</strong>
								<?php print "<br />".$trip['trip_tents']; ?>
							</p>
							<p>
								<strong>Canoe # / Colour</strong>
								<?php print "<br />".$trip['trip_canoes']; ?>
							</p>
						</td>	
						<td style="vertical-align: top;">
							<p>
								<strong>Itinerary</strong>
								<br />
								<?php
									$stmt = $conn -> prepare("SELECT * FROM fp_days
													WHERE day_trip_id = :day_trip_id
													ORDER BY day_num");
									$stmt -> bindValue(':day_trip_id', $id);
									$stmt -> execute();
									$itinerarylist = $stmt->fetchAll();

									foreach ($itinerarylist as $day) {print $day['day_num']." - ".$day['day_loc']." (".$day['day_date'].")<br />";}
								?>
							</p>
						</td>
					</tr>
				</table>
				<br />
			<?php
			}
			?>
	</body>
</html>