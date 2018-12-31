<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');

?>

<html>
	<head>
		<link href="reportprint.css" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>T-Shirt Report</title>
	</head>
	<body>
		<h1>T-Shirt Staff Order<img style="width: 35px;margin-left: 5px;vertical-align:-3px;" src="../../shared/sunman-black.png"/></h1>
		<table align=center>
			<tr> 
				<th align=left width="30%">Staff</th>
				<th width="70%">T-Shirt Size</th>
			</tr>
			<?php
				$staffquery = "SELECT * FROM staff 
														WHERE admin_archive = 'No'
														ORDER BY staff_shirtsize, staff_name_last, staff_name_common";
				foreach ($conn->query($staffquery) as $row)
				{?>
			<tr>				
				<td>
					<?php	print $row['staff_name_last'].", ".$row['staff_name_first']; ?>
				</td>
				<td>
					<?php	print $row['staff_shirtsize']; ?>
				</td>	
			</tr>
		<?php } ?>
		</table>
	</body>
</body>