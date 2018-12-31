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
		    	<div id="gl-title" class="pure-u-1 pure-u-md-2-3"> 
					<div id="mobile-menu-button"><i class="fa fa-bars"></i></div>
					<img id="gl-sunman" src="/index_files/header sunny.png" alt="sunman.png">
					<span>Gould Lake Outdoor Centre</span>            
				</div>
			    <div id="gl-subtitle" class="pure-u-1 pure-u-md-1-3">
			      	<span>Registration 2019</span> 
			    </div>
			</div>
		</div>
<script>

	$(document).ready(function(){
		
		$("#mobile-menu-button").click(function(e) {
			e.preventDefault();
			$("nav").toggle();
		});
		
	});

</script>
