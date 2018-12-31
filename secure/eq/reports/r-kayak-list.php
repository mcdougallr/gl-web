<!DOCTYPE html>
<head>
<link href="_reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
<title>Kayak Inventory</title>
</head>
<body>
	<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');

	$sortinput = "";
	if (isset($_GET['sort'])) {$sortinput = $_GET['sort'];}
	
	if ($sortinput == "rudder") {$sort = "kayak_rudder, kayak_name";}
	else if ($sortinput == "status") {$sort = "kayak_status, kayak_name";}
	else if ($sortinput == "type") {$sort = "kayak_type, kayak_name";}
	else if ($sortinput == "checked") {$sort = "kayak_checked, kayak_name";}
	else {$sort = "kayak_name";}

	$kayakquery = "SELECT * FROM eq_kayaks ORDER BY {$sort}";
							
	?>
	<h1><img src="../../shared/sunman-black.png" width="30" style="vertical-align: -3px;margin-right: 5px;"/>Kayak Inventory</h1>
	<table>
		<tr>
			<th><a class="sort_click" href="r-kayak-list.php">Name <i class="fa fa-repeat"></i></a></th>
			<th><a class="sort_click" href="r-kayak-list.php?sort=type">Type <i class="fa fa-repeat"></i></a></th>
			<th>Description</th>
			<th style="text-align: center;"><a class="sort_click" href="r-kayak-list.php?sort=rudder">Rudder <i class="fa fa-repeat"></i></a></th>
			<th style="text-align: center;"><a class="sort_click" href="r-kayak-list.php?sort=status">Status <i class="fa fa-repeat"></i></a></th>
			<th style="text-align: center;"><a class="sort_click" href="r-kayak-list.php?sort=checked">Checked <i class="fa fa-repeat"></i></a></th>
		</tr>
		
		<?php
		foreach ($conn->query($kayakquery)  as $kayak)
	    { ?>
			<tr>
				<td><?php print $kayak['kayak_name']; ?></td>
				<td><?php print $kayak['kayak_type']; ?></td>
				<td><?php print $kayak['kayak_description']; ?></td>
				<td style="text-align: center;"><?php print $kayak['kayak_rudder']; ?></td>
				<td style="text-align: center;"><?php print $kayak['kayak_status']; ?></td>
				<td style="text-align: center;"><?php print $kayak['kayak_checked']; ?></td>
			</tr>
		<?php					
		}
	?>
	</table>
</body>
</html>