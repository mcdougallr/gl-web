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
	    <link rel="stylesheet" href="../school-year-admin/_sya-header.css">
	    <link href="../school-year-admin/custom-theme/jquery-ui.css" rel="stylesheet" />
	    <script src="../../scripts/jquery/jquery-1.11.0.min.js"></script>
	    <script src="../shared/jquery-ui-1.10.3.custom.js"></script>
	    
	    <title>Gould Lake School Year</title>

  	</head>
  	<?php
	if(!isset($_SESSION)) {
	     session_start();
	}

  	$_SESSION['refer'] = "sya";

  	include ('../shared/dbconnect.php');
  	include ('../shared/functions.php');
  	include ('../shared/authenticate.php');
	
	if (isset($_SESSION['gl_staff_id']))
  	{
    	$stmt = $conn -> prepare("SELECT * FROM staff WHERE staff_id = :staff_id");
    	$stmt -> bindValue(':staff_id', $_SESSION['gl_staff_id']);
    	$stmt -> execute();
    	$staffaccess = $stmt -> fetch(PDO::FETCH_ASSOC);
  	}

?>

  	<body>
	    <div id="working"><img id="loading" src="../shared/loading.gif"/></div>
    	<!-- School Menu Navigation -->
	    <nav id="school-menu" class="nav-menu right-menu thin">
	    	<ul>
		    	<?php
		    		$schoolquery = "SELECT school_id, school_div, school_name, population FROM sy_schools ORDER BY school_div, school_name";
					foreach ($conn->query($schoolquery) as $school)
					{
	              		print "<li";
	              		if ($school['school_div'] != "E") {print " style=\"background: #444;\"";}
	              		print "><a href=\"sya-school.php?sid=".$school['school_id']."\" ";
		              	if ($school['population'] == 0 AND $school['school_div'] == "E") {print "style=\"color: #555;\" ";}
		              	print "class=\"gl-menu-item main-m\"><i class=\"fa fa-arrow-circle-right\"></i>";
		              	print $school['school_name']."</a></li>";
					}
		    	?>
		    </ul>
		    <div style="height: 50px;"></div>
	 	</nav>

		<!-- Report Menu Navigation -->
	    <nav id="report-menu" class="nav-menu right-menu reg">
	    	<ul>
		        <li><a href="reports/r-bookings-school.php" class="gl-menu-item main-m" target="_blank"><i class="fa fa-arrow-circle-right"></i>Bookings by School</a></li>
		        <li><a href="reports/r-bookings-date.php" class="gl-menu-item main-m" target="_blank"><i class="fa fa-arrow-circle-right"></i>Bookings by Date</a></li>
		        <li><a href="reports/r-quotas.php" class="gl-menu-item main-m" target="_blank"><i class="fa fa-arrow-circle-right"></i>Current Quotas</a></li>
		        <li><a href="reports/r-timesheet.php" class="gl-menu-item main-m" target="_blank"><i class="fa fa-arrow-circle-right"></i>Print Timesheets</a></li>   	
		        <li><a href="reports/r-funding.php" class="gl-menu-item main-m" target="_blank"><i class="fa fa-arrow-circle-right"></i>Funding Report</a></li> 
				 <li><a href="reports/r-staff-program.php" class="gl-menu-item main-m" target="_blank"><i class="fa fa-arrow-circle-right"></i>Program Staffing</a></li> 
	      	</ul>
	    	<div style="height: 50px;"></div>
	 	</nav>

    	<!-- INCLUDE DB MENU -->
    	<?php if ($staffaccess['staff_access'] > 1){ include ('../shared/dbmenu.php');}?>
    	
    	<!-- HEADER -->
    	<div id="header">
      		<div class="pure-g">
        		<div class="pure-u-1-3" style="text-align: left;height: 0;">
          			<div id="header-left">
            			<img id="db-menu-button" src="../shared/sunnyblack.png" class="menu-button" title="Database Menu">
            			<div id="top-text">School Year Admin</div>
          			</div>
        		</div>
        		<div class="pure-u-2-3" style="text-align: right;">
          			<div id="header-right">
          				<i id="book-button" class="fa fa-home mobile-title-icon" title="Book Visits"></i>
          				<i id="school-menu-button" class="fa fa-university mobile-title-icon menu-button" title="School Menu"></i>
          				<i id="report-menu-button" class="fa fa-print mobile-title-icon menu-button" title="Reports Menu"></i>
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
			if (!$(event.target).closest('#school-menu').length) {
				if ($('#school-menu').hasClass("active")) {
					$('#school-menu').removeClass("active");
					$('#school-menu').animate({
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
		
		$(".drop-menu").click(function () {
			id = $(this).attr("id");
			id = id.replace("-m", "");
			id = "#" + id + "-l";
			//alert(id);return false;
			if (!$(id).hasClass("active")) {
				$(".menu-l2").slideUp('fast');
				$(id).slideDown('fast');
				$(id).addClass("active");
			}
			else {
				$(id).slideUp('fast');
				$(id).removeClass("active");
			}
		});
		
		$('#book-button').click(function() {
			window.location = "index.php";
		});
		
		$('#logout-button').click(function() {
			window.location = "../shared/logout.php";
		});

	}); 
</script>
