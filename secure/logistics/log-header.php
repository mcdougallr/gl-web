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
	    <link rel="stylesheet" href="../logistics/_log-header.css">
	    <link href="../logistics/custom-theme/jquery-ui.css" rel="stylesheet" />
	    <script src="../../scripts/jquery/jquery-1.11.0.min.js"></script>
	    <script src="../shared/jquery-ui-1.10.3.custom.js"></script>
	    
	    <title>Gould Lake Logistics</title>

  	</head>
  	<?php
	if(!isset($_SESSION)) {
	     session_start();
	}

  	$_SESSION['refer'] = "log";

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
    	<!-- Main Menu-->
	    <nav id="main-menu" class="nav-menu right-menu reg">
	      	<ul>
		        <li><a id="bus-button" href="../calendar/cal-print-month.php?r=log&logt=b" target="_blank" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Bus Calendar</a></li>
		        <li><a id="driving-button" href="../calendar/cal-print-month.php?r=log&logt=d" target="_blank" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Driving Calendar</a></li>
		        <li><a id="eq-button" href="../calendar/cal-print-month.php?r=log&logt=e" target="_blank" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>EQ Calendar</a></li>
		        <li><a id="food-button" href="../calendar/cal-print-month.php?r=log&logt=f" target="_blank" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Food Calendar</a></li>
		        <li><a id="supervision-button" href="../calendar/cal-print-month.php?r=log&logt=s" target="_blank" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Supervision Calendar</a></li>
		        <li><a id="full-cal-button" href="../calendar/cal-print-month.phpr=log&logt=all" target="_blank" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Full Calendar</a></li>
	      	</ul>
	      	<div style="height: 50px;"></div>
    	</nav>
    	    	
    	<!-- Main Menu Navigation -->
	    <nav id="report-menu" class="nav-menu right-menu reg">
			<ul>
		        <li><a id="bus-schedule-date-button" href="reports/r-bus-date.php" target="_blank" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Bus Schedule by Date</a></li>
		        <li><a id="bus-schedule-route-button" href="reports/r-bus-route.php" target="_blank" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Bus Schedule by Route</a></li>
				<li><a id="bus-schedule-route-button" href="reports/r-bus-route-summary.php" target="_blank" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Bus Schedule Summary</a></li>
				<li><a id="bus-schedule-route-button" href="reports/r-bus-route-summary-cost.php" target="_blank" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Bus Schedule Summary (Cost)</a></li>
		        <li><a id="driving-schedule-button" class="gl-menu-item main-m" target="_blank" href="reports/r-drive.php"><i class="fa fa-arrow-circle-right"></i>Driving Schedule</a></li>
		        <li><a id="eq-schedule-button" href="reports/r-eq.php" target="_blank" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>EQ Schedule</a></li>	
		        <li><a id="food-schedule-button" href="reports/r-food.php" target="_blank" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Food Schedule</a></li>
		        <li><a id="super-schedule-button" href="reports/r-supervision.php" target="_blank" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Supervision Schedule</a></li>
		        <li><a id="office-schedule-button" href="reports/r-office.php" target="_blank" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Summer Office Schedule</a></li>
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
            			<div id="top-text">Logistics</div>
          			</div>
        		</div>
        		<div class="pure-u-13-24" style="text-align: right;">
          			<div id="header-right">
          				<i id="home-button" class="fa fa-home mobile-title-icon" title="Home"></i>
          				<?php if ($_SESSION['staff_access'] > 3) { ?><i id="newsfeed-button" class="fa fa-comment mobile-title-icon" title="Edit News Feed"></i> <?php } ?>
          				<i id="main-menu-button" class="fa fa-calendar mobile-title-icon menu-button" title="Calendars"></i>
          				<i id="report-menu-button" class="fa fa-print mobile-title-icon menu-button" title="Reports"></i>
            			<i id="logout-button" style="padding: 0 0 0 5px;" class="fa fa-sign-out" title="Logout"></i>
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
			if (!$(event.target).closest('#report-menu').length) {
				if ($('#report-menu').hasClass("active")) {
					$('#report-menu').removeClass("active");
					$('#report-menu').animate({
						right : '-277px'
					}, 300);
				}
			}
		});
		
		$('#home-button').click(function() {
			window.location = "/secure/logistics/index.php";
		});
		
		$('#newsfeed-button').click(function() {
			window.location = "edit-newsfeed.php";
		});
		
		$('#logout-button').click(function() {
			window.location = "../shared/logout.php";
		});

	}); 
</script>
