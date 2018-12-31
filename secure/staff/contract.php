<?php
	session_start();
	
	include ('../shared/dbconnect.php');
	include ('../shared/clean.php');
	include ('contractauthenticate.php');
	
	if (isset($_GET['staff'])){
		$staff_id = cleantext($_GET['staff']);
	}
	elseif (isset($_SESSION['gl_staff_id']))
	{
		$staff_id = $_SESSION['gl_staff_id'];
	}
	else {$staff_id = "";}
	
	if ($staff_id != "")
	{
	$stmt = $conn->prepare("SELECT * FROM staff 
														WHERE staff_id = :staff_id");
	  $stmt->bindParam(':staff_id', $staff_id);
	  $stmt->execute();
	  $staff = $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
?>

<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link href="_gl-staff-print.css" rel="stylesheet" type="text/css" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Gould Lake Contract 2017</title>

</head>
<body>
<div id="contract" class="pagebreak">
	<div style="text-align: center;">
		<h1 style="display: inline;">Gould Lake Outdoor Centre</h1>
		<img src="../shared/sunman-black.png" width="40"/>		
	</div>
  <h3 style="text-align: center;">Commitment to Work Schedule and Agreement to Duties and Obligations</h3>

  <p>I, <?php print $staff['staff_name_first']." ".$staff['staff_name_last']; ?>, am aware of and committed to the work and pay schedules as outlined on the Gould Lake Staff Database. Changes to this schedule will be made only after consultation with all concerned parties.</p>
  <p>I am aware of my duties and obligations as a Gould Lake staff member and agree to uphold the policies outlined by the Gould Lake Outdoor Centre and the Limestone District School Board.</p>
  <br />
  <br />
	<br />
  <table width=100%>
  	<tr>
  		<td width=40% style="border-top: 1px #222 solid;">Staff Signature</td>
  		<td width=10%></td>
  		<td width=40% style="border-top: 1px #222 solid;">Date</td>
  		<td width=10%></td>
  	</tr>
  </table>
  <br />
  <br />
  <br />
  <table width=100%>
  	<tr>
  		<td width=40% style="border-top: 1px #222 solid;">Nate Zahn<br />
        Outdoor Education Consultant<br />
        Gould Lake Outdoor Centre<br />
        Limestone District School Board</td>
      <td width=10%></td>
  		<td width=40% style="border-top: 1px #222 solid;vertical-align: top;">Date</td>
  		<td width=10%></td>
  	</tr>
  </table>
</div>


</body>
</html>