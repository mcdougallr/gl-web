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
		<title>Staff Contacts</title>
	</head>
	<body>
		<h1>Staff Contact Info<img style="width: 35px;margin-left: 5px;vertical-align:-3px;" src="../../shared/sunman-black.png"/></h1>
		<table align=center width="100%">
			<tr> 
				<th align=left>Staff</th>
				<th>Updated</th>
				<th>Home Phone</th>
				<th>Cell Phone</th>
				<th>Email</th>
				<th>Address</th>
			</tr>
			<?php
				$staffquery = "SELECT * FROM staff 
														WHERE admin_archive = 'No'
														ORDER BY staff_name_last, staff_name_common";
				foreach ($conn->query($staffquery) as $row)
				{?>
			<tr>				
				<td><?php	print $row['staff_name_last'].", ".$row['staff_name_common']; ?></td>
				<td><?php	print $row['staff_update_date']; ?></td>	
				<td><?php	print $row['staff_phone_home']; ?></td>	
				<td><?php	print $row['staff_phone_cell']; ?></td>	
				<td><?php	print $row['staff_email']; ?></td>	
				<td style="text-align: left;">
					<?php	print $row['staff_p_address'].", ".$row['staff_p_city']." ".$row['staff_p_province'].", ".$row['staff_p_postalcode'].", "; ?>
				</td>
			</tr>
		<?php } ?>
		</table>
	</body>
</body>