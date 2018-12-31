<!DOCTYPE html>
<html>
<head>
<link href="_medprint.css" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<title>Gould Lake Med Form Print</title>

<?php
	session_start();
	include ('../../shared/dbconnect.php');
	include ('../../shared/clean.php');
	include ('../../shared/authenticate.php');
?>

</head>
<body>
<?php

$count = count ($_POST['registration_id']);
$j = 0;

for ($i = 0; $i<$count; $i++)
{
	if (isset($_POST['printchecked'][$i]) and $_POST['printchecked'][$i] == 1)
	{
		$rid = cleantext($_POST['registration_id'][$i]);
		$stmt = $conn->prepare("SELECT * FROM ss_registrations
								LEFT JOIN ss_sessions ON ss_registrations.accepted_session = ss_sessions.session_id
								WHERE registration_id = :registration_id");
		$stmt->bindValue(':registration_id', $rid);
		$stmt->execute();						
		$student = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($j == 3 || $student['admin_flag_notes'] != "")
		{
			print "<div class=\"pagebreak\"></div>";
			$j = 0;
		}		
		?>

		<div id="wrapper">
			<div id="header">
  			<h1>
				<?php 
					print $student['student_name_last'].", ".$student['student_name_first']." (".$student['student_name_common'].") - ".$student['session_program_code'].$student['session_number']." "; 
					if ($student['confirm_photo'] == "N") {print "<i class=\"fa fa-camera\"></i>";}
					if ($student['confirm_social_media'] == "N") {print "<i class=\"fa fa-twitter\"></i>";}
				?>
            </h1>
    	</div>
			<div id="medform-details">
      	<table>
      		<tr>
      			<td width="25%">
	      			<label>DOB: </label>
							<?php print $student['student_dob']; ?><br>
              <label>Health Card #: </label><?php print $student['student_health_card']; ?><br>
              <label>Email: </label><?php print $student['student_email']; ?><br><br>
              
              <label>Living Arrangements:</label><br>
              <label>Custody: </label><?php print $student['student_custody']; ?><br>
              <?php if ($student['student_custody_details'] != "") {print $student['student_custody_details'];} ?><br><br>
              
              <label>Address</label><br>
              <?php print $student['g1_address']; ?><br>
              <?php print $student['g1_city'].", ".$student['g1_province']; ?><br>
              <?php print $student['g1_postalcode']; ?>
            </td>
            <td width="25%">
                <label>Contact 1 - <?php print $student['g1_relationship']; ?></label><br>
                <label>Name: </label><?php print $student['g1_name_first']." ".$student['g1_name_last']; ?><br>
                <?php if ($student['g1_p1'] != "") { ?><label>Phone 1: </label><?php print $student['g1_p1']; ?><br><?php } ?>
                <?php if ($student['g1_p2'] != "") { ?><label>Phone 2: </label><?php print $student['g1_p2']; ?><br><?php } ?>
                <?php if ($student['g1_notes'] != "") { ?><?php print $student['g1_notes']; ?><br><?php } ?>
            		<br>
                <label>Contact 2 - <?php print $student['g2_relationship']; ?></label><br>
                <label>Name: </label><?php print $student['g2_name_first']." ".$student['g2_name_last']; ?><br>
                <?php if ($student['g2_p1'] != "") { ?><label>Home: </label><?php print $student['g2_p1']; ?><br><?php } ?>
                <?php if ($student['g2_p2'] != "") { ?><label>Work: </label><?php print $student['g2_p2']; ?><br><?php } ?>
                <?php if ($student['g2_notes'] != "") { ?><?php print $student['g2_notes']; ?><br><?php } ?>
            		<br>
              	<label>Emergency Contact - <?php print $student['c_relationship']; ?></label><br>
                <label>Name: </label><?php print $student['c_name_first']." ".$student['c_name_last']; ?><br>
                <?php if ($student['c_p1'] != "") { ?><label>Home: </label><?php print $student['c_p1']; ?><br><?php } ?>
								<?php if ($student['c_p2'] != "") { ?><label>Cell: </label><?php print $student['c_p2']; ?><br><?php } ?>
            </td>
            <td width="25%">
	      			<div>
              	<label>Admin Notes</label><br>
                <?php print $student['admin_notes']; ?>
              </div>
            </td>
					</tr>
					<?php
						$laprint = 0;
						$medprint = 0;
						$dietprint = 0;
						if ($student['student_learning_accommodations'] != NULL) {$laprint = 1;}
						if ($student['student_health_hospitalized_details'] != NULL ||
												$student['student_health_meds_details'] != NULL ||
												$student['student_health_allergies_details'] != NULL ||
												$student['student_health_asthma_details'] != NULL ||
												$student['student_health_epipen_details'] != NULL ||
												$student['student_health_epilepsy_details'] != NULL ||
												$student['student_health_diabetes_details'] != NULL ||
												$student['student_health_counselor_details'] != NULL ||
												$student['student_health_anxiety_details'] != NULL ||
												$student['student_health_limitations_details'] != NULL ||
												$student['student_health_injuries_details'] != NULL ||
												$student['student_health_others_details'] != NULL) {$medprint = 1;}
						if ($student['student_health_dietary_details'] != NULL) {$dietprint = 1;}
					
						if ($laprint == 1 || $medprint == 1 || $dietprint == 1) {
					?>
					
					
					<tr>
						<td colspan=3>
              <?php if ($laprint == 1) { ?><label>LA</label><br><?php print "&nbsp;".$student['student_learning_accommodations']; ?><br><?php } ?>
							<?php if ($medprint == 1) { ?>
								<label>Med Info </label><br>
                <?php 
                if ($student['student_health_hospitalized_details'] != NULL) {print "&nbsp;Hospital: ".$student['student_health_hospitalized_details']."<br>";}
								if ($student['student_health_meds_details'] != NULL) {print "&nbsp;Meds: ".$student['student_health_meds_details']."<br>";}
								if ($student['student_health_allergies_details'] != NULL) {print "&nbsp;Allergies: ".$student['student_health_allergies_details']."<br>";}
								if ($student['student_health_epipen_details'] != NULL) {print "&nbsp;Epi: ".$student['student_health_epipen_details']."<br>";}
								if ($student['student_health_asthma_details'] != NULL) {print "&nbsp;Asthma: ".$student['student_health_asthma_details']."<br>";}								
								if ($student['student_health_epilepsy_details'] != NULL) {print "&nbsp;Epilepsy: ".$student['student_health_epilepsy_details']."<br>";}
								if ($student['student_health_diabetes_details'] != NULL) {print "&nbsp;Diabetes: ".$student['student_health_diabetes_details']."<br>";}
								if ($student['student_health_counselor_details'] != NULL) {print "&nbsp;Counselor: ".$student['student_health_counselor_details']."<br>";}
								if ($student['student_health_anxiety_details'] != NULL) {print "&nbsp;Anxiety: ".$student['student_health_anxiety_details']."<br>";}
								if ($student['student_health_limitations_details'] != NULL) {print "&nbsp;Limitations: ".$student['student_health_limitations_details']."<br>";}
								if ($student['student_health_injuries_details'] != NULL) {print "&nbsp;Injuries: ".$student['student_health_injuries_details']."<br>";} 
								if ($student['student_health_others_details'] != NULL) {print "&nbsp;Other: ".$student['student_health_others_details']."<br>";} ?>
							<?php } ?>
              <?php if ($dietprint == 1) { ?><label>Dietary </label><br><?php print "&nbsp;".$student['student_health_dietary_details']; }?>
          	</td>	
            </tr>
			<?php } 
				if ($student['admin_flag_notes'] != "") {print "<tr><td style=\"white-space:pre-wrap;\" colspan=3>".$student['admin_flag_notes']."</td></tr>"; $j = 2;}
			?>
        </table>
        <?php $j++; ?>
     	</div>
  	</div> 
    <br>                 
		<?php 
	}
}?>
</body>
