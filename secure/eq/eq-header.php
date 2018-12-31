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
	    <link rel="stylesheet" href="_eq-header.css">
	    <link href="custom-theme/jquery-ui.css" rel="stylesheet" />
	    <script src="../../scripts/jquery/jquery-1.11.0.min.js"></script>
	    <script src="../shared/jquery-ui-1.10.3.custom.js"></script>
	    
	    <title>Gould Lake EQ</title>

  	</head>
  	<?php
	if(!isset($_SESSION)) {
	     session_start();
	}

  	$_SESSION['refer'] = "eq";

  	include ('../shared/dbconnect.php');
  	include ('../shared/functions.php');
  	include ('../shared/authenticate.php');
	
	if (isset($_SESSION['gl_staff_id']))
  	{
    	$stmt = $conn -> prepare("SELECT * FROM staff 
													WHERE staff_id = :staff_id");
    	$stmt -> bindValue(':staff_id', $_SESSION['gl_staff_id']);
    	$stmt -> execute();
    	$staffaccess = $stmt -> fetch(PDO::FETCH_ASSOC);
  	}

?>
<style>
	::-webkit-input-placeholder {color: #FFF;font-weight: 300;font-size: .9em;}
	:-moz-placeholder {color: #FFF;font-weight: 300;font-size: .9em;}
	::-moz-placeholder {color: #FFF;font-weight: 300;font-size: .9em;}
	:-ms-input-placeholder {color: #FFF;font-weight: 300;font-size: .9em;}
	.search i {
		margin-left: 10px;
	}
</style>

  	<body>
	    <div id="working"><img id="loading" src="../shared/loading.gif"/></div>
    	
    	<!-- Pack Menu Navigation -->
	    <nav id="type-menu" class="nav-menu right-menu thin">
	    	<ul>
		    	<?php
		    		$packquery = "SELECT item_type_id, item_type_name FROM eq_item_types ORDER BY item_type_name"; 
	            	foreach($conn->query($packquery) as $pack)
	              	{
	              		print "<li><a href=\"index.php?tid=";
		              	print $pack['item_type_id']."\" class=\"gl-menu-item main-m\"><i class=\"fa fa-list-alt\"></i>";
		              	print $pack['item_type_name']."</a></li>";
					}
		    	?>
	    	</ul>
	    	<div style="height: 50px;"></div>
	 	</nav>
    	    	 	
    	<!-- Item Menu Navigation -->
	    <nav id="item-menu" class="nav-menu right-menu thin">
	    	<ul>
	    		<li><a id="add-item-button" href="edit-item.php" class="gl-menu-item main-m"><i class="fa fa-plus"></i>Add Item</a></li>
	    		<li><i style="color: #FFF;" class="fa fa-search"></i><input style="min-height: 20px;font-size: 1em;background: transparent;outline: none;border: none;padding: 3px 5px;color: #FFF;width: 75%;font-weight: 300;" type="text" class="search" id="searchid" placeholder="Search for Item" /></li>
	    		<div style="background: #555;" id="item-search-result">
			    	<?php
			    		$itemquery = "SELECT item_id, item_name FROM eq_items ORDER BY item_name"; 
		            	foreach($conn->query($itemquery) as $item)
		              	{
		              		print "<li><a href=\"edit-item.php?iid=";
			              	print $item['item_id']."\" class=\"gl-menu-item main-m search\"><i style=\"margin-left: 10px\" class=\"fa fa-caret-right\"></i>";
			              	print $item['item_name']."</a></li>";
						}
			    	?>
	    		</div>
	    	</ul>
	    	<div style="height: 50px;"></div>
	 	</nav>

		<!-- Pack Menu Navigation -->
	    <nav id="pack-menu" class="nav-menu right-menu thin">
	    	<ul>
	    		<li><a id="add-pack-button" href="edit-pack.php" class="gl-menu-item main-m"><i class="fa fa-plus"></i>Add Pack</a></li>
		    	<?php
		    		$packquery = "SELECT pack_id, pack_name FROM eq_packs ORDER BY pack_sort"; 
	            	foreach($conn->query($packquery) as $pack)
	              	{
	              		print "<li><a href=\"edit-pack.php?pid=";
						print $pack['pack_id']."\" class=\"gl-menu-item main-m\"><i class=\"fa fa-list-alt\"></i>";
						print $pack['pack_name']."</a></li>";
					}
		    	?>
	    	</ul>
	    	<div style="height: 50px;"></div>
	 	</nav>

   		<!-- Printout Navigation -->
	     <nav id="report-menu" class="nav-menu right-menu thin">
	    	<ul>
		    	<?php
		    		$packquery = "SELECT item_type_id, item_type_name FROM eq_item_types ORDER BY item_type_name"; 
	            	foreach($conn->query($packquery) as $pack)
	              	{
	              		print "<li><a target=\"_blank\" href=\"reports/r-inventory.php?tid=";
		              	print $pack['item_type_id']."\" class=\"gl-menu-item main-m\"><i class=\"fa fa-list-alt\"></i>";
		              	print $pack['item_type_name']."</a></li>";
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
        		<div class="pure-u-1-3" style="text-align: left;height: 0;">
          			<div id="header-left">
            			<img id="db-menu-button" src="../shared/sunnyblack.png" class="menu-button" title="Database Menu">
            			<div id="top-text">EQ</div>
          			</div>
        		</div>
        		<div class="pure-u-2-3" style="text-align: right;">
          			<div id="header-right">
          				<i id="type-menu-button" class="fa fa-bars mobile-title-icon menu-button" title="Type Menu"></i>
          				<i id="item-menu-button" class="fa fa-wrench mobile-title-icon menu-button" title="Item Menu"></i>
          				<i id="pack-menu-button" class="fa fa-suitcase mobile-title-icon menu-button" title="Pack Menu"></i>
          				<i id="report-menu-button" class="fa fa-print mobile-title-icon menu-button" title="Report Menu"></i>
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
			if (!$(event.target).closest('#type-menu').length) {
				if ($('#type-menu').hasClass("active")) {
					$('#type-menu').removeClass("active");
					$('#type-menu').animate({
						right : '-277px'
					}, 300);
				}
			}
			if (!$(event.target).closest('#item-menu').length) {
				if ($('#item-menu').hasClass("active")) {
					$('#item-menu').removeClass("active");
					$('#item-menu').animate({
						right : '-277px'
					}, 300);
				}
			}
			if (!$(event.target).closest('#pack-menu').length) {
				if ($('#pack-menu').hasClass("active")) {
					$('#pack-menu').removeClass("active");
					$('#pack-menu').animate({
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
		
		$(".search").keyup(function(){ 
			var searchid = $(this).val();
			
		    $.ajax({
			    type: "POST",
			    url: "process-item-search.php",
			    data: {searchterm: searchid},
			    success: function(html)
			    {
			    	$("#item-search-result").html(html).show();
			    }
		    });
			return false;    
		});
		
		$('#logout-button').click(function() {
			window.location = "../shared/logout.php";
		});

	}); 
</script>
