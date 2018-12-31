<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>

<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/clean.php');
	include ('../../shared/authenticate.php');
	
		$stmt = $conn->prepare("SELECT * FROM staff
								WHERE admin_archive = 'No'
								ORDER BY staff_name_last, staff_name_first");
		$stmt->execute();						
		$student_results = $stmt->fetchAll();

		
?>
</head>
<body>
	<div style="text-align: center;">
		<h1 style="display: inline;">Gould Lake Outdoor Centre</h1>
		<img src="sunman-black.png" width="30"/>
		<h2>Dietary Details - Staff</h2>
	</div>
  <table align="center">
  	<tr>
  		<th>#</th>
  		<th class="left">Last Name</th>
  		<th class="left">First Name</th>
  		<th class="left">Dietary Details</th>
  	</tr> 	
  		<?php
  		$count = 1;
  		foreach ($student_results as $student)
			{
  			 print "<tr><td>".$count."</td>";
  			 print "<td class=\"left\">".$student['staff_name_last']."</td>";
  			 print "<td class=\"left\">".$student['staff_name_first']."</td>";
  			 print "<td class=\"left\">".$student['staff_health_dietary']."</td></tr>";
				 $count++;
			} ?>
 	</table>
</body>
</html>
