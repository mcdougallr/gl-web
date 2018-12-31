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
	    <link rel="stylesheet" href="_sa-header.css">
	    <link href="custom-theme/jquery-ui.css" rel="stylesheet" />
	    <script src="../../scripts/jquery/jquery-1.11.0.min.js"></script>
	    <script src="../shared/jquery-ui-1.10.3.custom.js"></script>
	    
	    <title><?php print $page_title; ?></title>

  	</head>
  	<?php
	if(!isset($_SESSION)) {
	     session_start();
	}

  	$_SESSION['refer'] = "sa";

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
    	<!-- Main Menu Currently Unused-->
	    <nav id="main-menu" class="nav-menu right-menu reg">
	      	<ul>
		        <li><a id="home-button" href="index.php" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Who's Working Where</a></li>
		        <li><a id="edit-session-button" class="gl-menu-item main-m" href="sta-session-edit.php"><i class="fa fa-arrow-circle-right"></i>Edit Pay Period</a></li>
		        <li><a id="add-button" href="process-new-student.php" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Add Student</a></li>	
		        <li><a id="flag-button" href="sta-flags.php" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Student Flags</a></li>
	      	</ul>
	      	<div style="height: 50px;"></div>
    	</nav>
    	    	
    	<!-- Main Menu-->
	    <nav id="report-menu" class="nav-menu right-menu reg">
	      	<ul>
	        	<li><a href="reports/r-current.php" class="gl-menu-item main-m" target="_blank"><i class="fa fa-arrow-circle-right"></i>Current Staff</a></li>
		        <li><a href="sa-report-pick-medforms.php" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Med Forms <i class="fa fa-list-alt"></i></a></li>
		        <li><a href="sa-report-pick-paysheets.php" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Paysheets <i class="fa fa-list-alt"></i></a></li>
		        <li><a href="reports/r-contact.php" class="gl-menu-item main-m" target="_blank"><i class="fa fa-arrow-circle-right"></i>Contact Info</a></li>
		        <li><a href="reports/r-admin.php" class="gl-menu-item main-m" target="_blank"><i class="fa fa-arrow-circle-right"></i>Admin Report</a></li>
		        <li><a href="reports/r-BEd.php" class="gl-menu-item main-m" target="_blank"><i class="fa fa-arrow-circle-right"></i>Staff Type</a></li>	
		        <li><a href="reports/r-certs.php" class="gl-menu-item main-m" target="_blank"><i class="fa fa-arrow-circle-right"></i>Cert Report</a></li>
		        <li><a href="reports/r-paytotal.php" class="gl-menu-item main-m" target="_blank"><i class="fa fa-arrow-circle-right"></i>Pay Totals</a></li>
		        <li><a href="reports/r-tshirt.php" class="gl-menu-item main-m" target="_blank"><i class="fa fa-arrow-circle-right"></i>T-Shirt Order</a></li>
		        <li><a href="reports/r-wishlist.php" class="gl-menu-item main-m" target="_blank"><i class="fa fa-arrow-circle-right"></i>Wishlist</a></li>		        	
	      	</ul>
	      	<div style="height: 50px;"></div>
    	</nav>
    	
    	<!-- SESSION EDIT Menu -->
    	<nav id="session-menu" class="nav-menu right-menu reg">
	      	<ul>
	      		<?php
				$findsessionquery = "SELECT * FROM gl_staff_sessions
													ORDER BY staffing_session_sort, staffing_session_id";

				foreach ($conn->query($findsessionquery) as $row)
				{
					print "<li><a href=\"sa-session-edit.php?sid=".$row['staffing_session_id']."\" class=\"gl-menu-item\">";
					print $row['staffing_session_program_code'].$row['staffing_session_number']." - ".$row['staffing_session_portion'];
					print "</a></li>";
				}
				?> 
	      	</ul>
	      	<div style="height: 50px;"></div>
    	</nav>
    	
    	<!-- Student Menu -->
    	<nav id="staff-menu" class="nav-menu right-menu thin">
	      	<ul>
	      		<li><i style="color: #FFF;" class="fa fa-search"></i><input style="min-height: 20px;font-size: 1em;background: transparent;outline: none;border: none;padding: 3px 5px;color: #FFF;width: 75%;font-weight: 300;" type="text" class="search" id="searchid" placeholder="Search for Staff" /></li>
	      		<div id="staff-search-result">
	      		<?php
				$findstaffquery = "SELECT staff_id, staff_name_last, staff_name_common, admin_archive, admin_summer, admin_summer_confirmed, admin_summer_display FROM staff
													ORDER BY admin_archive, staff_name_last, staff_name_common";

				foreach ($conn->query($findstaffquery) as $row)
				{?>
					<li style="background:  #<?php if ($row['admin_archive'] == "Yes") {print "999";} else {print "222";}?>">
						<a href="../staff-edit/index.php?sid=<?php print $row['staff_id'];?>#profile" class="gl-menu-item staff-m"><?php print $row['staff_name_last'].", ".$row['staff_name_common'];?></a>
						<?php if ($row['admin_archive'] == "No" && $row['admin_summer'] != "") 
						{?>
							<div style="font-size: .6em;color: #AAA">
								<i style="margin: 0;" class="fa fa-<?php
									if ($row['admin_summer_display'] == 1){print "eye";}
									elseif ($row['admin_summer_confirmed'] == 1){print "check";}
						 			else {print "times";}?>"></i>
								<?php print $row['admin_summer'];?>
							</div>
							<?php } ?>
					</li>
					<?php
				}
				?> 
				</div>
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
            			<div id="top-text">Staff Admin</div>
          			</div>
        		</div>
        		<div class="pure-u-13-24" style="text-align: right;">
          			<div id="header-right">
          				<i id="return-home-button" class="fa fa-home mobile-title-icon" title="Home"></i>
          				<i id="report-menu-button" class="fa fa-print mobile-title-icon menu-button" title="Report Menu"></i>
          				<i id="session-menu-button" class="fa fa-calendar mobile-title-icon menu-button" title="Session Menu"></i>
          				<i id="staff-menu-button" class="fa fa-user mobile-title-icon menu-button" title="Staff Menu"></i>
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
			if (!$(event.target).closest('#report-menu').length) {
				if ($('#report-menu').hasClass("active")) {
					$('#report-menu').removeClass("active");
					$('#report-menu').animate({
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
		
		$(".search").keyup(function(){ 
			var searchid = $(this).val();
			
		    $.ajax({
			    type: "POST",
			    url: "process-staff-search.php",
			    data: {searchterm: searchid},
			    success: function(html)
			    {
			    	$("#staff-search-result").html(html).show();
			    }
		    });
			return false;    
		});
		
		$('#return-home-button').click(function() {
			window.location = "index.php";
		});
		
		$('#logout-button').click(function() {
			window.location = "../shared/logout.php";
		});

	}); 
</script>
