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
	    <link rel="stylesheet" href="_sch-header.css">
	    <link href="../school-year/custom-theme/jquery-ui.css" rel="stylesheet" />
	    <script src="../../scripts/jquery/jquery-1.11.0.min.js"></script>
	    <script src="../shared/jquery-ui-1.10.3.custom.js"></script>
	    
	    <title>Gould Lake School Admin</title>

  	</head>
  	<?php
	if(!isset($_SESSION)) {
	     session_start();
	}
	
	$_SESSION['refer'] = "sch";

  	include ('../shared/dbconnect.php');
  	include ('../shared/functions.php');
	
?>
  	<body>
    	//Menu
    	<nav id="main-menu" class="nav-menu right-menu reg">
	      	<ul>
                <li><a id="home-button" href="index.php" class="gl-menu-item main-m"><i class="fa fa-calendar"></i>scheduled classes</a></li>
		        <li><a id="edit-session-button" class="gl-menu-item main-m" href="booking.php"><i class="fa fa-arrow-circle-right"></i>book classes</a></li>
		        <li><a id="add-button" href="http://outed.limestone.on.ca/aboutus/contactfind.php" class="gl-menu-item main-m"><i class="fa fa-map-marker"></i>directions to gl</a></li>	
		        <li><a id="flag-button" href="http://outed.limestone.on.ca/teachers/overview.php" class="gl-menu-item main-m"><i class="fa fa-info"></i>program info</a></li>
	      	</ul>
	      	<div style="height: 50px;"></div>
    	</nav>    	
    	
    	<!-- HEADER -->
    	<div id="header">
      		<div class="pure-g">
        		<div class="pure-u-2-3" style="text-align: left;height: 0;">
          			<div id="header-left">
            			<img id="db-menu-button" src="../shared/sunnyblack.png" title="Database Menu">
            			<div id="top-text">Gould Lake School Admin</div>
          			</div>
        		</div>
        		<div class="pure-u-1-3" style="text-align: right;">
          			<div id="header-right">
          				<i id="main-menu-button" style="padding: 0 0 0 5px;" class="fa fa-bars menu-button" title="Menu"></i>
            			<i id="logout-button" style="padding: 0 0 0 5px;" class="fa fa-sign-out menu-button" title="Logout"></i>
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
		
		
		
		$('#logout-button').click(function() {
			window.location = "../shared/logout.php";
		});

	}); 
</script>
