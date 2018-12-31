<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/functions.php');
include ('../shared/jsfunctions.php');

if (!isset($_SESSION['page1'])) {$_SESSION['page1'] = 1;}
if (!isset($_SESSION['page2'])) {$_SESSION['page2'] = 0;}
if (!isset($_SESSION['page3'])) {$_SESSION['page3'] = 0;}
if (!isset($_SESSION['page4'])) {$_SESSION['page4'] = 0;}
if (!isset($_SESSION['page5'])) {$_SESSION['page5'] = 0;}
if (!isset($_SESSION['page6'])) {$_SESSION['page6'] = 0;}
if (!isset($_SESSION['page7'])) {$_SESSION['page7'] = 0;}
if (!isset($_SESSION['page8'])) {$_SESSION['page8'] = 0;}
if (!isset($_SESSION['page9'])) {$_SESSION['page9'] = 0;}
if (!isset($_SESSION['registration_id'])) {$_SESSION['registration_id'] = 0;}

?>
<!DOCTYPE html>
<html>
  <head>
  	<title>Gould Lake Registration System</title>
  	
  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
		<link rel="stylesheet" href="../../scripts/pure6/pure-min.css">
		<link rel="stylesheet" href="../../scripts/pure6/grids-responsive-min.css">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>      
    <link href="custom-theme/jquery-ui-1.10.3.custom.css" rel="stylesheet" /> 
    <link href="regcss.css" rel="stylesheet" type="text/css" />
    
    <script src="//code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="jquery-ui-1.10.3.custom.js"></script>
    <script type="text/javascript" src="../../scripts/parsley/dist/parsley.min.js"></script> 
  </head>
 	<body>
 		<div id="working"><img id="loading" src="loading.gif"/></div>
	  <div id="reg-header">
	  	<div id=header-top class="pure-g">
		    <div id="gl-title" class="pure-u-1 pure-u-md-1-2"> 
					<div id="mobile-menu-button"><i class="fa fa-bars"></i></div>
					<img id="gl-sunman" src="/index_files/header sunny.png" alt="sunman.png">
					<span>Gould Lake Outdoor Centre</span>            
				</div>
		    <div id="gl-subtitle" class="pure-u-1 pure-u-md-1-2">
		      <span>Outdoor Leadership Program 2016</span> 
		    </div>
		  </div>
		  <?php
		  if ($page > 0 and $page < 9)
		  {?>
		  	<nav>
			  	<div id="full-menu">
			    	<div class="nav-text">
			    	<?php
				    	if ($_SESSION['page1'] == 0 && $page != 1) {print "Email";}
							elseif ($page == 1) {print "<div style=\"font-weight: 400;\">Email</div>";}
				    	else 
							{
								print "<a class=\"pagecomplete\" href=\"index.php\">Email</a>";
							}
						?>
						</div>
						<div class="nav-line-icon">
							<?php 
							if ($_SESSION['page1'] == 1 and $_SESSION['page2'] == 1){print "<i class=\"fa fa-arrows-h\"></i>";}
							else {print "<i class=\"fa fa-long-arrow-right\"></i>";}
							?>
						</div>
						<div class="nav-text">		    	
			    	<?php
				    	if ($_SESSION['page2'] == 0 && $page != 2) {print "Profile";}
							elseif ($page == 2) {print "<div class=\"currentpage\">Profile</div>";}
							else  
							{
								print "<a class=\"pagecomplete\" href=\"page2.php\">Profile</a>";
							}
						?>
						</div>
						<div class="nav-line-icon">
						<?php 
							if ($_SESSION['page2'] == 1 and $_SESSION['page3'] == 1){print "<i class=\"fa fa-arrows-h\"></i>";}
							else {print "<i class=\"fa fa-long-arrow-right\"></i>";}
							?>
						</div>
						<div class="nav-text">		    	
			    	<?php
				    	if ($_SESSION['page3'] == 0 && $page != 3) {print "Contact";}
							elseif ($page == 3) {print "<div class=\"currentpage\">Contact</div>";}
							else  
							{
								print "<a class=\"pagecomplete\" href=\"page3.php\">Contact</a>";
							}
						?>
						</div>
						<div class="nav-line-icon">						
						<?php 
							if ($_SESSION['page3'] == 1 and $_SESSION['page4'] == 1){print "<i class=\"fa fa-arrows-h\"></i>";}
							else {print "<i class=\"fa fa-long-arrow-right\"></i>";}
							?>
						</div>		  
						<div class="nav-text">		    	  	
			    	<?php
				    	if ($_SESSION['page4'] == 0 && $page != 4) {print "Medical";}
							elseif ($page == 4) {print "<div class=\"currentpage\">Medical</div>";}
							else  
							{
								print "<a class=\"pagecomplete\" href=\"page4.php\">Medical</a>";
							}
						?>
						</div>
						<div class="nav-line-icon">							
						<?php 
							if ($_SESSION['page4'] == 1 and $_SESSION['page5'] == 1){print "<i class=\"fa fa-arrows-h\"></i>";}
							else {print "<i class=\"fa fa-long-arrow-right\"></i>";}
							?>
						</div>	
						<div class="nav-text">		    		    	
			    	<?php
				    	if ($_SESSION['page5'] == 0 && $page != 5) {print "Additional";}
							elseif ($page == 5) {print "<div class=\"currentpage\">Additional</div>";}
							else  
							{
								print "<a class=\"pagecomplete\" href=\"page5.php\">Experience</a>";
							}
						?>
						</div>						
						<div class="nav-line-icon">	    	
						<?php 
							if ($_SESSION['page5'] == 1 and $_SESSION['page6'] == 1){print "<i class=\"fa fa-arrows-h\"></i>";}
							else {print "<i class=\"fa fa-long-arrow-right\"></i>";}
							?>
						</div>		    	
			    	
						<div class="nav-text">		    	
							<?php
					    	if ($_SESSION['page6'] == 0 && $page != 6) {print "Submit";}
								elseif ($page == 6) {print "<div class=\"currentpage\">Submit</div>";}
								else 
								{
									print "<a class=\"pagecomplete\" href=\"page6.php\">Submit</a>";
								}
							?>
						</div>
						<div class="nav-line-icon"><i class="fa fa-long-arrow-right"></i></div>
						<div class="nav-text">Done!</div>
					</div>		
			  </nav>
		  <?php
		  }
			?>
		</div>
	
<script>

	$(document).ready(function(){
		
		$("#mobile-menu-button").click(function(e) {
			e.preventDefault();
			$("#full-menu").toggle();
		});
		
	});

</script>
