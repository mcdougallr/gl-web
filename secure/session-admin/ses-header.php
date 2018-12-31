<!doctype html>
<html lang="en">
  	<head>

	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	    <link rel="stylesheet" href="../../scripts/pure6/pure-min.css">
	    <link rel="stylesheet" href="../../scripts/pure6/grids-responsive-min.css">
	    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
	    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>

	    <link rel="stylesheet" href="../shared/_gl-common.css">
	    <link rel="stylesheet" href="_ses-header.css">
	    <link href="custom-theme/jquery-ui.css" rel="stylesheet" />
	    <script src="../../scripts/jquery/jquery-1.11.0.min.js"></script>
	    <script src="../shared/jquery-ui-1.10.3.custom.js"></script>
	    
	    <title><?php print $page_title; ?></title>

  	</head>
  	<?php
	if(!isset($_SESSION)) {
	     session_start();
	}

  	$_SESSION['refer'] = "ses";

  	include ('../shared/dbconnect.php');
  	include ('../shared/functions.php');
  	include ('../shared/authenticate.php');

  	if (!isset($_SESSION['date'])) {$_SESSION['date'] = date("Y-m-d");}
	
	if (isset($_SESSION['gl_staff_id']))
  	{
    	$stmt = $conn -> prepare("SELECT * FROM staff 
													WHERE staff_id = :staff_id");
    	$stmt -> bindValue(':staff_id', $_SESSION['gl_staff_id']);
    	$stmt -> execute();
    	$staffaccess = $stmt -> fetch(PDO::FETCH_ASSOC);
  	}
?>

  	<body>
	    <div id="working"><img id="loading" src="../shared/loading.gif"/></div>
   	    	
    	<!-- Session Menu -->
    	<nav id="session-menu" class="nav-menu right-menu thin">
	      	<ul>
	      		<?php
				$findsessionquery = "SELECT session_id, session_program_code, session_number FROM ss_sessions ORDER BY session_sortorder";
				
				foreach ($conn->query($findsessionquery) as $session)
				{
					print "<li><a href=\"ses-session-edit.php?sid=".$session['session_id']."\" class=\"gl-menu-item staff-m\">";
					print $session['session_program_code'].$session['session_number'];
					print "</a></li>";
				}
				?> 
	      	</ul>
	      	<div style="height: 50px;"></div>
    	</nav>
    	
		<!-- Float Plan Print Menu -->
    	<nav id="printtrip-menu" class="nav-menu right-menu thin">
	      	<ul>
	      		<?php
				$tripquery = "SELECT trip_id, trip_section_id, trip_name FROM fp_trips ORDER BY trip_section_id, trip_name";
				foreach ($conn->query($tripquery) as $trip)
				{
					$trip_id = $trip['trip_id'];
					$stmt = $conn -> prepare("SELECT staff_name_common FROM fp_tripstaff
															LEFT JOIN staff ON staff.staff_id = fp_tripstaff.tripstaff_staff_id
															WHERE tripstaff_trip_id = '$trip_id' AND tripstaff_tt = 0
															ORDER BY staff_name_common");
					$stmt -> execute();
    				$stafflist = $stmt -> fetchAll();
					if ($stafflist == NULL) {$staffnames = "(Not Staffed)";}
					else {
						$staffnames = "(";
						$first = 1;
						foreach ($stafflist as $staff)
						{
							if ($first != 1) {$staffnames = $staffnames."/";}
							$staffnames = $staffnames.$staff['staff_name_common'];
							$first = 0;
						}
						$staffnames = $staffnames.")";
					}
					print "<li><a href=\"reports/r-floatplan.php?sid=".$trip['trip_section_id']."&tid=".$trip['trip_id']."\" class=\"gl-menu-item staff-m\" target=\"_blank\">";
					print $trip['trip_name']."<span style=\"margin-left: 5px;font-size: .8em;vertical-align: 2px;color:#999\">".$staffnames."</span>";
					print "</a></li>";
				}
				?> 
	      	</ul>
	      	<div style="height: 50px;"></div>
    	</nav>

    	<!-- INCLUDE DB MENU -->
    	<?php if ($staffaccess['staff_access'] > 1){ include ('../shared/dbmenu.php');}?>
    	
    	<!-- HEADER -->
    	<div id="header">
      		<div class="pure-g">
        		<div class="pure-u-11-24" style="text-align: left;height: 0;">
          			<div id="header-left">
            			<img id="db-menu-button" src="../shared/sunnyblack.png" class="menu-button" title="Database Menu">
            			<div id="top-text">Session Admin</div>
          			</div>
        		</div>
        		<div class="pure-u-13-24" style="text-align: right;">
          			<div id="header-right">
          				<i id="return-home-button" class="fa fa-home mobile-title-icon" title="Home"></i>
          				<i id="session-menu-button" class="fa fa-pencil-square-o mobile-title-icon menu-button" title="Edit Session"></i>
          				<i id="printtrip-menu-button" class="fa fa-print mobile-title-icon menu-button" title="Print Float Plan"></i>
            			<i id="logout-button" style="padding-right: 0;" class="fa fa-sign-out" title="Logout"></i>
          			</div>
        		</div>
      		</div>
    	</div>
    	
    	<!-- MAIN -->
    	<div id="main">

<script>
	$(document).ready(function() {

		// MENU BEHAVIOUR
		$('.menu-button').click(function() {
			id = $(this).attr("id");
			id = id.replace("-button", "");
			id = "#" + id;
			if (!$(id).hasClass("active")) {
				if ($('.left-menu').hasClass("active")) {
					$('.left-menu').removeClass("active");
					$('.left-menu').animate({
						left : '-277px'
					}, 300);
				}
				if ($('.right-menu').hasClass("active")) {
					$('.right-menu').removeClass("active");
					$('.right-menu').animate({
						right : '-277px'
					}, 300);
				}
				$(id).addClass("active");
				if ($(id).hasClass("right-menu")) {$(id).animate({right : '0'}, 300);}
				else {$(id).animate({left : '0'}, 300);}
			} 
			else {
				$(id).removeClass("active");
				if ($(id).hasClass("right-menu")) {$(id).animate({right : '-277px'}, 300);}
				else {$(id).animate({left : '-277px'}, 300);}
			}
			return false;
		});

		$(document).click(function(event) {
			if (!$(event.target).closest('#main-menu').length) {
				if ($('#main-menu').hasClass("active")) {
					$('#main-menu').removeClass("active");
					$('#main-menu').animate({
						right : '-277px'
					}, 300);
				}
			}
			if (!$(event.target).closest('#staff-menu').length) {
				if ($('#staff-menu').hasClass("active")) {
					$('#staff-menu').removeClass("active");
					$('#staff-menu').animate({
						right : '-277px'
					}, 300);
				}
			}
			if (!$(event.target).closest('#printtrip-menu').length) {
				if ($('#printtrip-menu').hasClass("active")) {
					$('#printtrip-menu').removeClass("active");
					$('#printtrip-menu').animate({
						right : '-277px'
					}, 300);
				}
			}
			if (!$(event.target).closest('#session-menu').length) {
				if ($('#session-menu').hasClass("active")) {
					$('#session-menu').removeClass("active");
					$('#session-menu').animate({
						right : '-277px'
					}, 300);
				}
			}
		});
	
		$('#return-home-button').click(function() {
			window.location = "index.php";
		});
		
		$('#logout-button').click(function() {
			window.location = "../shared/logout.php";
		});

	}); 
</script>
