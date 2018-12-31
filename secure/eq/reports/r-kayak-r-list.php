<!DOCTYPE html>
<head>
<link href="_reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
<title>Kayak Repairs</title>
</head>
<body>
	<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');

	?>
	<h1><img src="../../shared/sunman-black.png" width="30" style="vertical-align: -3px;margin-right: 5px;"/>Kayak Repairs</h1>
	<table>
		<tr>
			<th>Name</th>
			<th>Type</th>
			<th>Description</th>
			<th>Repair</th>
			<th>Repair Notes</th>
		</tr>
		
		<?php
		$bgcolor = "#F2F2F2";
		$kayakquery = "SELECT * FROM eq_kayaks ORDER BY kayak_name";
		foreach ($conn->query($kayakquery)  as $kayak)
	    { 
	    	$stmt = $conn->prepare("SELECT * FROM eq_kayak_repairs 
	    												LEFT JOIN eq_kayak_repair_types ON eq_kayak_repair_types.kayak_repair_type_id = kayak_repair_type
	    												WHERE kayak_repair_kayak_id = :kayak_repair_kayak_id AND kayak_repair_complete = 0");
			$stmt->bindValue(':kayak_repair_kayak_id', $kayak['kayak_id']);
			$stmt->execute();
			$repairs = $stmt->fetchAll();
			$repaircount = count($repairs);
			
			if ($repaircount > 0)
			{
				if ($bgcolor == "#F2F2F2") {$bgcolor = "#FFF";}
				else {$bgcolor = "#F2F2F2";}?>
				<tr style="background: <?php print $bgcolor;?>">
					<td rowspan="<?php print $repaircount; ?>"><?php print $kayak['kayak_name']; ?></td>
					<td rowspan="<?php print $repaircount; ?>"><?php print $kayak['kayak_type']; ?></td>
					<td rowspan="<?php print $repaircount; ?>"><?php print $kayak['kayak_description']; ?></td>
					<?php
					$repaircycle = 0;
					foreach ($repairs  as $repair)
	    			{
	    				if ($repaircycle > 0) {?><tr style="background: <?php print $bgcolor;?>"><?php } ?>
						<td><?php print $repair['kayak_repair_type_name']; ?></td>
						<td><?php print $repair['kayak_repair_notes']; ?></td>
					</tr>
					<?php
					$repaircycle++;
				}
			}				
		}
	?>
	</table>
</body>
</html>