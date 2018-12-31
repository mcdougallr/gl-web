<!doctype html>
<html lang="en">
  	<head>

	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	    <link rel="stylesheet" href="../../scripts/pure6/pure-min.css">
	    <link rel="stylesheet" href="../../scripts/pure6/grids-responsive-min.css">
	    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>

	    <link rel="stylesheet" href="../shared/_gl-common.css">
	    <link rel="stylesheet" href="_sta-header.css">
	    <link href="custom-theme/jquery-ui.css" rel="stylesheet" />
	    <script src="../../scripts/jquery/jquery-1.11.0.min.js"></script>
	    <script src="../shared/jquery-ui-1.10.3.custom.js"></script>
	    
	    <title><?php print $page_title; ?></title>

  	</head>
  	<?php
	if(!isset($_SESSION)) {
	     session_start();
	}

  	$_SESSION['refer'] = "sta";

  	include ('../shared/dbconnect.php');
  	include ('../shared/clean.php');
  	include ('../shared/functions.php');
  	include ('../shared/authenticate.php');

  	if (!isset($_SESSION['student_id'])) {$_SESSION['student_id'] = 0;}

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
		        <li><a id="home-button" href="index.php" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Home</a></li>
		        <li><a id="edit-session-button" class="gl-menu-item main-m" href="sta-session-edit.php"><i class="fa fa-arrow-circle-right"></i>Edit Session Info</a></li>
		        <li><a id="add-button" href="process-new-student.php" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Add Student</a></li>	
		        <li><a id="flag-button" href="sta-flags.php" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>Student Flags</a></li>
	      	</ul>
	      	<div style="height: 50px;"></div>
    	</nav>
    	    	
    	<!-- Main Menu Navigation -->
	    <nav id="flag-menu" class="nav-menu right-menu thin">
	    	<li><a href="sta-flags.php" class="gl-menu-item main-m"><i class="fa fa-arrow-circle-right"></i>All Flags</a></li>
	    	<?php
	    		$sessionquery = "SELECT session_id, session_program_code, session_number FROM ss_sessions WHERE session_program_code != 'ST' ORDER BY session_sortorder"; 
            	foreach($conn->query($sessionquery) as $session)
              	{
              		print "<li><a href=\"sta-flags.php?sid=";
              	print $session['session_id']."\" class=\"gl-menu-item main-m\"><i class=\"fa fa-arrow-circle-right\"></i>";
              	print $session['session_program_code'].$session['session_number']."</a></li>";
				}
	    	?>
	 	</nav>
    
    	<!-- Assign Menu Navigation -->
	    <nav id="assign-menu" class="nav-menu right-menu reg">
	    	<?php
	    		$programquery = "SELECT program_id, program_name FROM ss_programs WHERE program_code != 'ST' ORDER BY program_sortorder"; 
            	foreach($conn->query($programquery) as $program)
              	{
              		print "<li><a href=\"sta-assign-students.php?pid=";
              	print $program['program_id']."\" class=\"gl-menu-item main-m\"><i class=\"fa fa-arrow-circle-right\"></i>";
              	print $program['program_name']."</a></li>";
				}
	    	?>
	 	</nav>
    	
    	<!-- Student Menu -->
    	<nav id="student-menu" class="nav-menu right-menu thin">
	      	<ul>
	      		<li><a id="add-button" href="process-new-student.php" class="gl-menu-item main-m"><i class="fa fa-plus"></i>Add Student</a></li>	
				<li><i style="color: #FFF;" class="fa fa-search"></i><input style="min-height: 20px;font-size: 1em;background: transparent;outline: none;border: none;padding: 3px 5px;color: #FFF;width: 75%;font-weight: 300;" type="text" class="search" id="searchid" placeholder="Search for Student" /></li>
	    		<div style="background: #555;" id="student-search-result">
		      		<?php
					$findstudentquery = "SELECT registration_id, student_name_last, student_name_common, accepted_session FROM ss_registrations 
														ORDER BY student_name_last, student_name_first";
	
					foreach ($conn->query($findstudentquery) as $student)
					{
						print "<li><a href=\"../student-edit/index.php?sid=".$student['registration_id']."#profile\" class=\"gl-menu-item student-m\">";
						print $student['student_name_last'].", ".$student['student_name_common'];
						if ($student['accepted_session'] != 0) {
							$stmt = $conn->prepare("SELECT * FROM ss_sessions WHERE session_id = :session_id");
							$stmt->bindValue(':session_id', $student['accepted_session']);
							$stmt->execute();
							$session = $stmt->fetch(PDO::FETCH_ASSOC);
							print "  (".$session['session_program_code'].$session['session_number'].")";
						}
						print "</a></li>";
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
        		<div class="pure-u-1-2" style="text-align: left;height: 0;">
          			<div id="header-left">
            			<img id="db-menu-button" src="../shared/sunnyblack.png" class="menu-button" title="Database Menu">
            			<div id="top-text">Student Admin</div>
          			</div>
        		</div>
        		<div class="pure-u-1-2" style="text-align: right;">
          			<div id="header-right">
          				<i id="go-home-button" class="fa fa-home mobile-title-icon menu-button" title="Home"></i>
          				<i id="flag-menu-button" class="fa fa-flag mobile-title-icon menu-button" title="Flags"></i>
          				<i id="assign-menu-button" class="fa fa-pencil mobile-title-icon menu-button" title="Assign Menu"></i>
          				<i id="student-menu-button" class="fa fa-user mobile-title-icon menu-button" title="Student Menu"></i>
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
			if (!$(event.target).closest('#student-menu').length) {
				if ($('#student-menu').hasClass("active")) {
					$('#student-menu').removeClass("active");
					$('#student-menu').animate({
						right : '-277px'
					}, 300);
				}
			}
			if (!$(event.target).closest('#assign-menu').length) {
				if ($('#assign-menu').hasClass("active")) {
					$('#assign-menu').removeClass("active");
					$('#assign-menu').animate({
						right : '-277px'
					}, 300);
				}
			}
			if (!$(event.target).closest('#flag-menu').length) {
				if ($('#flag-menu').hasClass("active")) {
					$('#flag-menu').removeClass("active");
					$('#flag-menu').animate({
						right : '-277px'
					}, 300);
				}
			}
		});
		
		$(".search").keyup(function(){ 
			var searchid = $(this).val();
			
		    $.ajax({
			    type: "POST",
			    url: "process-student-search.php",
			    data: {searchterm: searchid},
			    success: function(html)
			    {
			    	$("#student-search-result").html(html).show();
			    }
		    });
			return false;    
		});
		
		$('#go-home-button').click(function() {
			window.location = "index.php";
		});
		
		$('#logout-button').click(function() {
			window.location = "../shared/logout.php";
		});

	}); 
</script>
