<!DOCTYPE html>
<head>
<link href="_reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
<title>Canoe Repairs</title>
</head>
<body>
	<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');

	$sortinput = "";
	if (isset($_GET['sort'])) {$sortinput = $_GET['sort'];}

	?>
	<h1><img src="../../shared/sunman-black.png" width="30" style="vertical-align: -3px;margin-right: 5px;"/>Canoe Repairs</h1>
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
		$canoequery = "SELECT * FROM eq_canoes ORDER BY canoe_name";
		foreach ($conn->query($canoequery)  as $canoe)
	    { 
	    	$stmt = $conn->prepare("SELECT * FROM eq_canoe_repairs 
	    												LEFT JOIN eq_canoe_repair_types ON eq_canoe_repair_types.canoe_repair_type_id = canoe_repair_type
	    												WHERE canoe_repair_canoe_id = :canoe_repair_canoe_id AND canoe_repair_complete = 0");
			$stmt->bindValue(':canoe_repair_canoe_id', $canoe['canoe_id']);
			$stmt->execute();
			$repairs = $stmt->fetchAll();
			$repaircount = count($repairs);
			
			if ($repaircount > 0)
			{
				if ($bgcolor == "#F2F2F2") {$bgcolor = "#FFF";}
				else {$bgcolor = "#F2F2F2";}?>
				<tr style="background: <?php print $bgcolor;?>">
					<td rowspan="<?php print $repaircount; ?>"><?php print $canoe['canoe_name']; ?></td>
					<td rowspan="<?php print $repaircount; ?>"><?php print $canoe['canoe_type']; ?></td>
					<td rowspan="<?php print $repaircount; ?>"><?php print $canoe['canoe_description']; ?></td>
					<?php
					$repaircycle = 0;
					foreach ($repairs  as $repair)
	    			{
	    				if ($repaircycle > 0) {?><tr style="background: <?php print $bgcolor;?>"><?php } ?>
						<td><?php print $repair['canoe_repair_type_name']; ?></td>
						<td><?php print $repair['canoe_repair_notes']; ?></td>
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