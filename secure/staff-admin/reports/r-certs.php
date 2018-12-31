<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/clean.php');
	include ('../../shared/authenticate.php');

?>

<html>
	<head>
		<link href="reportprint.css" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Certification Report</title>
	</head>
	<body>
		<h1>Certification Overview<img style="width: 35px;margin-left: 5px;vertical-align:-3px;" src="../../shared/sunman-black.png"/></h1>
		<table>
			<tr> 
				<th align=left>Staff</th>
				<th>Lifesaving</th>
				<th>First-Aid</th>
			</tr>
			<?php
				$staffquery = "SELECT * FROM staff 
														WHERE admin_archive = 'No'
														ORDER BY staff_name_last, staff_name_common";
				foreach ($conn->query($staffquery) as $row)
				{?>
			<tr>				
				<td width="25%">
					<?php	print $row['staff_name_last'].", ".$row['staff_name_first']; ?>
				</td>
                <td width="30%"
					<?php	
						$swimexpire = date('Y')."-09-01";
						if ($row['cert_swim_exp'] < $swimexpire) {print " style=\"background: #FFF;\"";}
						else {print " style=\"background: #39AC6A;\"";} ?>>
					<?php	print $row['cert_swim_num']." (".$row['cert_swim_exp'].")"; ?>
				</td>	
				<td width="30%"
					<?php	
						$faexpire = date('Y-m-d', strtotime('-2 year', strtotime($swimexpire)));
						if ($row['cert_FA_date'] < $faexpire) {print " style=\"background: #FFF;\"";}
						else {print " style=\"background: #39AC6A;\"";} ?>>
					<?php	print $row['cert_FA']." (".$row['cert_FA_date'].")"; ?>
				</td>	
			</tr>
		<?php } ?>
		</table>
	</body>
</body>