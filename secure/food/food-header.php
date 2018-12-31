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
	    <link rel="stylesheet" href="_food-header.css">
	    <script src="../../scripts/jquery/jquery-1.11.0.min.js"></script>
	    <script src="../shared/jquery-ui-1.10.3.custom.js"></script>
	    
	    <title>Gould Lake Food</title>

  	</head>
  	<style>
		.nav-menu a {
			font-size: .8em;
		}
	</style>
  	<?php
	if(!isset($_SESSION)) {
	     session_start();
	}

  	$_SESSION['refer'] = "food";

  	include ('../shared/dbconnect.php');
  	include ('../shared/functions.php');
	include ('../shared/clean.php');
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

  	<body>
	    <div id="working"><img id="loading" src="../shared/loading.gif"/></div>
    	
    	<!-- Section Menu -->
    	<nav id="section-menu" class="nav-menu right-menu thin">
	      	<ul>
	      		<li><a href="edit-pack-list.php?sid=0" class="gl-menu-item staff-m">Pre Summer</a></li>
	      		<?php
				$findsectionquery = "SELECT section_id, session_program_code, session_number, section_name FROM ss_session_sections
									LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_session_sections.section_session_id
									WHERE session_program_code != 'Winter' AND session_program_code != 'ST' 
									ORDER BY session_sortorder";

				foreach ($conn->query($findsectionquery) as $section)
				{
					print "<li><a href=\"edit-pack-list.php?sid=".$section['section_id']."\" class=\"gl-menu-item staff-m\">";
					print $section['session_program_code'].$section['session_number']." - ".$section['section_name'];
					print "</a></li>";
				}
				?> 
	      	</ul>
	      	<div style="height: 50px;"></div>
    	</nav>
    	    	 	
    	<!-- Item Menu Navigation -->
	    <nav id="item-menu" class="nav-menu right-menu thin">
	    	<ul>
	    		<li><a id="add-item-button" href="edit-item.php?iid=new" class="gl-menu-item main-m"><i class="fa fa-plus"></i>Add Item</a></li>
	    		<li><a id="print-totals-button" href="reports/print-all-items-totals.php" target="_blank" class="gl-menu-item main-m"><i class="fa fa-print"></i>Print Item Totals Report</a></li>
	    		<li><i style="color: #FFF;" class="fa fa-search"></i><input style="min-height: 20px;font-size: .8em;background: transparent;outline: none;border: none;padding: 3px 5px;color: #FFF;width: 75%;font-weight: 300;" type="text" class="search" id="searchid" placeholder="Search for Item" /></li>
	    		<div style="background: #555;" id="item-search-result">
			    	<?php
			    		$itemquery = "SELECT item_id, item_name FROM fd_items ORDER BY item_name"; 
		            	foreach($conn->query($itemquery) as $item)
		              	{
		              		print "<li><a href=\"edit-item.php?iid=";
			              	print $item['item_id']."\" class=\"gl-menu-item main-m search\"><i class=\"fa fa-caret-right\"></i>";
			              	print $item['item_name']."</a></li>";
						}
			    	?>
	    		</div>
	    	</ul>
	    	<div style="height: 50px;"></div>
	 	</nav>

		<!-- Order Menu Navigation -->
	    <nav id="order-menu" class="nav-menu right-menu thin">
	    	<ul>
	    		<li><a id="print-order-button" href="edit-orders.php?sid=print" class="gl-menu-item main-m"><i class="fa fa-plus"></i>Print Totals</a></li>
		    	<?php
		    		$findsupplierquery = "SELECT supplier_id, supplier_name FROM fd_suppliers ORDER BY supplier_name";
					foreach ($conn->query($findsupplierquery) as $supplier)
						{
							print "<li><a href=\"edit-order-list.php?sid=";
			              	print $supplier['supplier_id']."\" class=\"gl-menu-item main-m\"><i class=\"fa fa-caret-right\"></i>";
			              	print $supplier['supplier_name']."</a></li>";
						}
					?>	
	    	</ul>
	    	<div style="height: 50px;"></div>
	 	</nav>
	 	
	 	<!-- Supplier Menu Navigation -->
	    <nav id="supplier-menu" class="nav-menu right-menu thin">
	    	<ul>
	    		<li><a id="add-supplier-button" href="edit-supplier.php?sid=new" class="gl-menu-item main-m"><i class="fa fa-plus"></i>Add Supplier</a></li>
		    	<?php
		    		$findsupplierquery = "SELECT supplier_id, supplier_name FROM fd_suppliers ORDER BY supplier_name";
					foreach ($conn->query($findsupplierquery) as $supplier)
						{
							print "<li><a href=\"edit-supplier.php?sid=";
			              	print $supplier['supplier_id']."\" class=\"gl-menu-item main-m\"><i class=\"fa fa-caret-right\"></i>";
			              	print $supplier['supplier_name']."</a></li>";
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
            			<div id="top-text">Food</div>
          			</div>
        		</div>
        		<div class="pure-u-2-3" style="text-align: right;">
          			<div id="header-right">
          				<i id="section-menu-button" class="fa fa-bars mobile-title-icon menu-button" title="Section Menu"></i>
          				<i id="item-menu-button" class="fa fa-cart-arrow-down mobile-title-icon menu-button" title="Item Menu"></i>
          				<i id="order-menu-button" class="fa fa-file-text-o mobile-title-icon menu-button" title="Order Menu"></i>
          				<i id="supplier-menu-button" class="fa fa-truck mobile-title-icon menu-button" title="Supplier Menu"></i>
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
