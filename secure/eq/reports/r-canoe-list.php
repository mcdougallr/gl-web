<!DOCTYPE html>
<head>
<link href="_reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
<title>Canoe Inventory</title>
</head>
<body>
	<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');

	$sortinput = "";
	if (isset($_GET['sort'])) {$sortinput = $_GET['sort'];}
	
	if ($sortinput == "outfitting") {$sort = "canoe_outfitting, canoe_name";}
	else if ($sortinput == "status") {$sort = "canoe_status, canoe_name";}
	else if ($sortinput == "type") {$sort = "canoe_type, canoe_name";}
	else if ($sortinput == "checked") {$sort = "canoe_checked, canoe_name";}
	else {$sort = "canoe_name";}

	$canoequery = "SELECT * FROM eq_canoes ORDER BY {$sort}";
							
	?>
	<h1><img src="../../shared/sunman-black.png" width="30" style="vertical-align: -3px;margin-right: 5px;"/>Canoe Inventory</h1>
	<table>
		<tr>
			<th><a class="sort_click" href="r-canoe-list.php">Name <i class="fa fa-repeat"></i></a></th>
			<th><a class="sort_click" href="r-canoe-list.php?sort=type">Type <i class="fa fa-repeat"></i></a></th>
			<th>Description</th>
			<th style="text-align: center;"><a class="sort_click" href="r-canoe-list.php?sort=outfitting">Outfitting <i class="fa fa-repeat"></i></a></th>
			<th style="text-align: center;"><a class="sort_click" href="r-canoe-list.php?sort=status">Status <i class="fa fa-repeat"></i></a></th>
			<th style="text-align: center;"><a class="sort_click" href="r-canoe-list.php?sort=checked">Checked <i class="fa fa-repeat"></i></a></th>
		</tr>
		
		<?php
		foreach ($conn->query($canoequery)  as $canoe)
	    { ?>
			<tr>
				<td><?php print $canoe['canoe_name']; ?></td>
				<td><?php print $canoe['canoe_type']; ?></td>
				<td><?php print $canoe['canoe_description']; ?></td>
				<td style="text-align: center;"><?php print $canoe['canoe_outfitting']; ?></td>
				<td style="text-align: center;"><?php print $canoe['canoe_status']; ?></td>
				<td style="text-align: center;"><?php print $canoe['canoe_checked']; ?></td>
			</tr>
		<?php					
		}
	?>
	</table>
</body>
</html>