<?php
	if(!isset($_SESSION)) {
	     session_start();
	}

  	$_SESSION['refer'] = "sa";

  	include ('../shared/dbconnect.php');
  	include ('../shared/functions.php');
  	include ('../shared/authenticate.php');
	
	$stmt = $conn -> prepare("SELECT * FROM staff 
												WHERE staff_id = :staff_id");
	$stmt -> bindValue(':staff_id', $_SESSION['gl_staff_id']);
	$stmt -> execute();
	$staffaccess = $stmt -> fetch(PDO::FETCH_ASSOC);
  	
  	if (isset($_GET['sid'])) {$staff_id = $_GET['sid'];}
	else {header("Location: ../staff-admin/index.php");}
	
	$stmt = $conn -> prepare("SELECT * FROM staff
												WHERE staff_id = :staff_id");
	$stmt -> bindValue(':staff_id', $staff_id);
	$stmt -> execute();
	$staff = $stmt -> fetch(PDO::FETCH_ASSOC);
?>

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
	    <link rel="stylesheet" href="_gl-staff-edit.css">
	    <link href="custom-theme/jquery-ui.css" rel="stylesheet" />
	    <script src="../../scripts/jquery/jquery-1.11.0.min.js"></script>
	    <script src="../shared/jquery-ui-1.10.3.custom.js"></script>
	    
	    <title>GL - <?php print $staff['staff_name_common']." ".$staff['staff_name_last'];?></title>

  	</head>
  	<body>
	    <div id="working"><img id="loading" src="../shared/loading.gif"/></div>
    	<!-- Main Menu Navigation -->
	    <nav id="main-menu" class="nav-menu right-menu reg">
	      	<li><a id="profile-button" href="#profile" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Profile</a></li>
	        <li><a id="admin-button" href="#admin" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Admin</a></li>
	        <li><a id="emerg-button" href="#emerg" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Emerg Info</a></li>
	        <li><a id="schedule-button" href="#staffschedule" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Schedule</a></li>
	        <li><a id="full-calendar-button" href="#full-calendar" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Calendar</a></li>
	        <li><a id="payroll-button" href="#payroll" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Payroll</a></li>
	        <li><a id="uploads-button" href="#uploads" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>File Uploads</a></li>
	        <li><a id="uploads-button" href="../staff/contract.php?staff=<?php print $staff_id; ?>" target="_blank" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Print Contract</a></li>
	        <div style="height: 50px;"></div>
    	</nav>
    	
    	<!-- INCLUDE DB MENU -->
    	<?php if ($staffaccess['staff_access'] > 1){ include ('../shared/dbmenu.php');}?>
    	
    	<!-- HEADER -->
    	<div id="header">
      		<div class="pure-g">
        		<div class="pure-u-2-3" style="text-align: left;height: 0;">
          			<div id="header-left">
            			<img id="db-menu-button" src="../shared/sunnyblack.png" class="menu-button" title="Database Menu">
            			<div id="top-text">
            				<div id="last-name" style="display: inline"><?php print $staff['staff_name_last'].", "; ?></div>
            				<div id="first-name" style="display: inline"><?php print $staff['staff_name_common']; ?></div>
            				<br />
            				<div id="staff_status" style="font-size: .8em;margin-top: -2px;"></div>
            			</div>
          			</div>
        		</div>
        		<div class="pure-u-1-3" style="text-align: right;">
          			<div id="header-right">
          				<i id="back-button" class="fa fa-arrow-left mobile-title-icon" title="Back"></i>
          				<i id="main-menu-button" class="fa fa-bars mobile-title-icon menu-button" title="Main Menu"></i>
          				<i id="logout-button" style="padding-right: 0;" class="fa fa-sign-out" title="Logout"></i>
          			</div>
        		</div>
      		</div>
    	</div>
    	
    	<!-- MAIN -->
    	<div id="main">
	
	      	<!-- PROFILE -->
	      	<section id="profile" class="page">
		        <h1>Staff Profile</h1>
		        <form id="profile_form" class="pure-form pure-form-stacked" method="post" action="">
			          <div class="pure-g">
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				            	<div class="input-padding">
					                <label>Last Name</label>
					                <input class="pure-input-1 gl-input" name="staff_name_last" type="text" onconkeypress="return noenter()" placeholder="Last Name" value="<?php print $staff['staff_name_last']; ?>">
				             	</div>
			            	</div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
					            <div class="input-padding">
						              <label>Formal First Name</label>
						              <input class="pure-input-1 gl-input" name="staff_name_first" type="text" onkeypress="return noenter()" placeholder="Formal First Name" value="<?php print $staff['staff_name_first']; ?>">
					            </div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				              	<div class="input-padding">
				                	<label>Common First Name</label>
				                	<input class="pure-input-1 gl-input" name="staff_name_common" type="text" onkeypress="return noenter()" placeholder="Common First Name" value="<?php print $staff['staff_name_common']; ?>">
				              	</div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				              	<div class="input-padding">
				                	<label>Email <a href="mailto:<?php print $staff['staff_email']; ?>"><i class="fa fa-envelope" style="color: #222;"></i></a></label>
				                	<input class="pure-input-1 gl-input" name="staff_email" type="text" onkeypress="return noenter()" placeholder="Email" value="<?php print $staff['staff_email']; ?>">
				              	</div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				              	<div class="input-padding">
					                <label>Home Phone <a href="tel:<?php print $staff['staff_phone_home']; ?>"><i class="fa fa-phone" style="color: #222;"></i></a></label>
					                <input class="pure-input-1 gl-input" name="staff_phone_home" type="tel" pattern="\d\d\d\-\d\d\d\-\d\d\d\d" onkeypress="return noenter()" value="<?php print $staff['staff_phone_home']; ?>">
				             	</div>
		            		</div>
		           			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
		              			<div class="input-padding">
					                <label>Cell Phone <a href="tel:<?php print $staff['staff_phone_cell']; ?>"><i class="fa fa-phone" style="color: #222;"></i></a></label>
					                <input class="pure-input-1 gl-input" name="staff_phone_cell" type="tel" pattern="\d\d\d\-\d\d\d\-\d\d\d\d" onkeypress="return noenter()" value="<?php print $staff['staff_phone_cell']; ?>" title="###-###-####">
				              	</div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				              	<div class="input-padding">
					                <label>Date of Birth</label>
					                <input id="staff_dob" style="-webkit-appearance: none;" class="pure-input-1 gl-main-date gl-input" type="date" name="staff_dob" onkeypress="return noenter()" value="<?php print $staff['staff_dob']; ?>">
				              	</div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				              	<div class="input-padding">
					                <label>Sex</label>
					                <select style="-webkit-appearance: none;" class="pure-input-1 gl-input" onkeypress="return noenter()" name="staff_sex" placeholder="Sex">
				                  		<option value="">-</option>
										<option value="Female" <?php if ($staff['staff_sex'] == "Female"){print " selected";} ?>>Female</option>
				                  		<option value="Male" <?php if ($staff['staff_sex'] == "Male"){print " selected";} ?>>Male</option>
				                	</select>
				              	</div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				              	<div class="input-padding">
					                <label>T-Shirt Size (Unisex)</label>
					                <input class="pure-input-1 gl-input" name="staff_shirtsize" type="text" onkeypress="return noenter()" placeholder="T-Shirt Size" value="<?php print $staff['staff_shirtsize']; ?>">
				              	</div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
					              <div class="input-padding">
						              <label>Street</label>
						              <input class="pure-input-1 gl-input" name="staff_p_address" type="text" onkeypress="return noenter()" placeholder="Address" value="<?php print $staff['staff_p_address']; ?>">
					              </div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				              	<div class="input-padding">
				                	<label>City</label>
				                	<input class="pure-input-1 gl-input" name="staff_p_city" type="text" onkeypress="return noenter()" placeholder="Address" value="<?php print $staff['staff_p_city']; ?>">
				              	</div>
				            </div>
				            <div class="pure-u-1-2 pure-u-sm-1-4 pure-u-md-1-6 pure-u-lg-1-8">
				              	<div class="input-padding">
				                	<label>Province</label>
				                	<input class="pure-input-1 gl-input" name="staff_p_province" type="text" onkeypress="return noenter()" placeholder="Address" value="<?php print $staff['staff_p_province']; ?>">
				              	</div>
				            </div>
				            <div class="pure-u-1-2 pure-u-sm-1-4 pure-u-md-1-6 pure-u-lg-1-8">
				              	<div class="input-padding">
				                	<label>Postal Code</label>
				                	<input class="pure-input-1 gl-input" name="staff_p_postalcode" type="text" onkeypress="return noenter()" placeholder="Address" value="<?php print $staff['staff_p_postalcode']; ?>">
				              	</div>
				            </div>
		          		</div>
		        	</form>
	      		</section>
	
	      	<!-- ADMIN  -->
	      	<section id="admin" class="page">
		        <h1>Administrative Details</h1>
		        <form id="admin_form" class="pure-form pure-form-stacked">
		          	<div class="pure-g">
			            <div class="pure-u-1">
			              	<div class="input-padding">
				                <label for="admin_notes">Admin Notes</label>
				                <textarea class="pure-input-1 gl-input textarea_expand" id="admin_notes" name="admin_notes"><?php print $staff['admin_notes']; ?></textarea>
			              	</div>
			              	<hr />
			            </div>
			            
						<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4 adminchecklist">
			              	<div class="input-padding">
			            		 <div class="checkstyle"> 
	          						<input name="admin_contract" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_contract'] == 1){print " checked";} ?>>
						          	<label>Work Agreement</label>
						        </div>
						        <div class="checkstyle"> 
	          						<input name="admin_offdec" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_offdec'] == 1){print " checked";} ?>>
             						<label>Offence Declaration</label>
						        </div>
						  	</div>
						</div>
		              	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4 adminchecklist">
			              	<div class="input-padding">
			            		<div class="checkstyle"> 
	          						<input name="admin_payroll_form" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_payroll_form'] == 1){print " checked";} ?>>
						          	<label>Payroll Info</label>
						        </div>
						        <div class="checkstyle"> 
	          						<input name="admin_deposit" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_deposit'] == 1){print " checked";} ?>>
              						<label>Bank Info</label>
						        </div>
						  	</div>
						</div>
						<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4 adminchecklist">
			              	<div class="input-padding">
			            		<div class="checkstyle"> 
	          						<input name="admin_tax" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_tax'] == 1){print " checked";} ?>>
              						<label>Tax Forms</label>
						        </div>
						        <div class="checkstyle"> 
	          						<input name="admin_policy_chat" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_policy_chat'] == 1){print " checked";} ?>>
             						<label>Policy Chat</label>
						        </div>
						  	</div>
						</div>
						<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4 adminchecklist">
			              	<div class="input-padding">
			            		<div class="checkstyle"> 
	          						<input name="admin_CPIC" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_CPIC'] == 1){print " checked";} ?>>
						          	<label>CPIC on File</label>
						        </div>
						        <div class="checkstyle"> 
	          						<input name="admin_OSHA" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_OSHA'] == 1){print " checked";} ?>>
              						<label>OSHA</label>
						        </div>
						  	</div>
						</div>
						<div class="pure-u-1"><hr /></div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
			              	<div class="input-padding">
				                <label>Staff Type</label>
				                <select class="pure-input-1 gl-input" onkeypress="return noenter()" name="admin_stafftype">
			                  		<option <?php if ($staff['admin_stafftype'] == "Field Staff"){print " selected";} ?>>Field Staff</option>
			                  		<option <?php if ($staff['admin_stafftype'] == "Field Staff BEd"){print " selected";} ?>>Field Staff BEd</option>
			                	</select>
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
			              	<div class="input-padding">
				                <label>Archived?</label>
				                <select class="pure-input-1 gl-input" onkeypress="return noenter()" name="admin_archive">
			                  		<option <?php if ($staff['admin_archive'] == "Yes"){print " selected";} ?>>Yes</option>
			                  		<option <?php if ($staff['admin_archive'] == "No"){print " selected";} ?>>No</option>
			                	</select>
			              	</div>
			            </div>
			           	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
			              	<div class="input-padding">
				                <label>Access Level</label>
				                <select class="pure-input-1 gl-input" onkeypress="return noenter()" name="staff_access">
			                  		<option <?php if ($staff['staff_access'] == 0){print " selected";} ?>>0</option>
			                  		<option <?php if ($staff['staff_access'] == 1){print " selected";} ?>>1</option>
			                  		<option <?php if ($staff['staff_access'] == 2){print " selected";} ?>>2</option>
			                  		<option <?php if ($staff['staff_access'] == 3){print " selected";} ?>>3</option>
			                  		<option <?php if ($staff['staff_access'] == 4){print " selected";} ?>>4</option>
			                	</select>
			              	</div>
			            </div>			            
			            <div class="pure-u-1 pure-u-sm-1-4 pure-u-md-1-8">
			              	<div class="input-padding">
				                <label for="admin_yearhired">Year Hired</label>
				                <input class="pure-input-1 gl-input" id="admin_yearhired" name="admin_yearhired" onkeypress="return noenter()" type="text" value="<?php print $staff['admin_yearhired']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-4 pure-u-md-1-8">
			              	<div class="input-padding">
				                <label>LDSB #</label>
				                <input class="pure-input-1 gl-input" id="admin_LDSBnum" name="admin_LDSBnum" onkeypress="return noenter()" type="text" value="<?php print $staff['admin_LDSBnum']; ?>">
			              	</div>
			            </div>			            
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
			              	<div class="input-padding">
				                <label for="cert_swim">Swim Cert & NLS#</label>
				                <input class="pure-input-1 gl-input" id="cert_swim_num" name="cert_swim_num" onkeypress="return noenter()" type="text" value="<?php print $staff['cert_swim_num']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
			              	<div class="input-padding">
				                <label for="cert_swim_exp">Swim Expiry Date</label>
				                <input class="pure-input-1 gl-input" id="cert_swim_exp" name="cert_swim_exp" onkeypress="return noenter()" type="text" value="<?php print $staff['cert_swim_exp']; ?>">
			              	</div>
	            		</div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
			              	<div class="input-padding">
				                <label for="cert_FA">First-Aid Cert</label>
				                <input class="pure-input-1 gl-input" id="cert_FA" name="cert_FA" onkeypress="return noenter()" type="text" value="<?php print $staff['cert_FA']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
			              	<div class="input-padding">
				                <label for="cert_FA_date">First Aid Cert Date</label>
				                <input class="pure-input-1 gl-input" id="cert_FA_date" name="cert_FA_date" onkeypress="return noenter()" type="text" value="<?php print $staff['cert_FA_date']; ?>">
			              	</div>
			            </div>
		          	</div>
		        </form>
	      	</section>
	
		    <!-- EMERG -->
		  	<section id="emerg" class="page">
		        <h1>Emergency Information</h1>
		        <form id="emerg_form" method=post class="pure-form pure-form-stacked" method="post" action="">
		          	<div class="pure-g">
		            	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
			              	<div class="input-padding">
				                <label>Emergency Contact Last Name</label>
				                <input class="pure-input-1 gl-input" name="staff_econtact_name_last" type="text" placeholder="Last Name" value="<?php print $staff['staff_econtact_name_last']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
			              	<div class="input-padding">
				                <label>Emergency Contact First Name</label>
				                <input class="pure-input-1 gl-input" name="staff_econtact_name_first" type="text" placeholder="First Name" value="<?php print $staff['staff_econtact_name_first']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
			              	<div class="input-padding">
				                <label>Emergency Contact Relationship</label>
				                <input class="pure-input-1 gl-input" name="staff_econtact_relationship" type="text" onKeyPress="return noenter()" value="<?php print $staff['staff_econtact_relationship']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
			              	<div class="input-padding">
				                <label>Emergency Contact Phone #1 <a href="tel:<?php print $staff['staff_econtact_phone_day']; ?>"><i class="fa fa-phone" style="color: #222;"></i></a></label>
				                <input class="pure-input-1 gl-input" name="staff_econtact_phone_day" type="tel" onkeypress="return noenter()" value="<?php print $staff['staff_econtact_phone_day']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
			              	<div class="input-padding">
				                <label>Emergency Contact Phone #2 <a href="tel:<?php print $staff['staff_econtact_phone_evening']; ?>"><i class="fa fa-phone" style="color: #222;"></i></a></label>
				                <input class="pure-input-1 gl-input" name="staff_econtact_phone_evening" type="tel" onkeypress="return noenter()" value="<?php print $staff['staff_econtact_phone_evening']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
			              	<div class="input-padding">
				                <label>Health Card Number</label>
				                <input class="pure-u-1 gl-input" name="staff_health_card" type="text" onKeyPress="return noenter()" value="<?php print $staff['staff_health_card']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1">
			              	<div class="input-padding">
				                <label>Health Conditions</label>
				                <textarea class="pure-input-1 textarea_expand gl-input" name="staff_health_conditions"><?php print $staff['staff_health_conditions']; ?></textarea>
			              	</div>
			            </div>
			            <div class="pure-u-1">
			              	<div class="input-padding">
				                <label>Recent Injuries/Operations</label>
				                <textarea class="pure-input-1 textarea_expand gl-input" name="staff_health_injuries"><?php print $staff['staff_health_injuries']; ?></textarea>
			              	</div>
			            </div>
			            <div class="pure-u-1">
			              	<div class="input-padding">
				                <label>Dietary Restrictions</label>
				                <textarea class="pure-input-1 textarea_expand gl-input" name="staff_health_dietary"><?php print $staff['staff_health_dietary']; ?></textarea>
			              	</div>
			            </div>
		          	</div>
		        </form>
			</section>
	
	      	<!-- SCHEDULE -->
	      	<section id="staffschedule" class="page">
	        	<h1>Schedule</h1>
		        <div class="pure-g">
		          	<div class="pure-u-1 pure-u-md-1-3">
        				<form id="add-workday-form" class="pure-form pure-form-stacked"  method="post" action="">
		          			<h2>Schedule Info</h2>
							<div class="input-padding adminchecklist">
								<div class="checkstyle"> 
									<input name="admin_summer_confirmed" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_summer_confirmed'] == 1){print " checked";} ?>>
									<label>Summer Confirmed</label>
								</div>
							</div>
							<div class="input-padding adminchecklist">
								<div class="checkstyle"> 
									<input name="admin_summer_display" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_summer_display'] == 1){print " checked";} ?>>
									<label>Summer Display</label>
								</div>
							</div>
		          			<div class="input-padding">
				                <label for="admin_summer">Summer Brief</label>
				                <input class="pure-input-1 gl-input" id="admin_summer" name="admin_summer" onkeypress="return noenter()" type="text" value="<?php print $staff['admin_summer']; ?>">
			              	</div>
			              	<div class="input-padding">
				                <label for="admin_wishlist">Wishlist</label>
				                <input class="pure-input-1 gl-input" id="admin_wishlist" name="admin_wishlist" onkeypress="return noenter()" type="text" value="<?php print $staff['admin_wishlist']; ?>">
			              	</div>
			              	<h2>Add Session</h2>
			              	<div class="input-padding">
			            		<label>Session</label>
					            <select id="add-workday-session" class="pure-input-1" name="add-workday-session">
					            	<option value="">Select Section...</option>
					              	<?php
					          		$sectionsquery = "SELECT section_id, session_program_code, session_number,section_name FROM ss_session_sections 
	                      								LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_session_sections.section_session_id
					          							ORDER BY session_sortorder, section_sortorder";
									foreach ($conn->query($sectionsquery) as $section)
									{
					          			print "<option value={$section['section_id']}>".$section['session_program_code'];
										print $section['session_number']. " - ".$section['section_name']."</option>";
									}
					          		?>
					            </select>
					    	</div>
				     	</form>
			    	</div>
	    			<div class="pure-u-1 pure-u-md-2-3"> 
				      	<h2>Current Program Schedule</h2>
				      	<form id="delete-form" method="post" action="">
					        <table class="pure-table pure-table-bordered gl-staff-schedule">
					          <tr> 
					            <th><i class="fa fa-trash del-all"></i></th> 
					            <th>Date</th>
					            <th style="text-align: left;">Program</th>
					            <th>%</th>
					          </tr>
					          <?php
				              $stmt = $conn->prepare("SELECT workday_id, event_date, session_program_code, session_number, workday_percentage, summer_description
					              						FROM staff_workdays
	                      								LEFT JOIN schedule_events ON staff_workdays.workday_event_id = schedule_events.event_id
	                      								LEFT JOIN schedule_summer ON schedule_events.event_type_id = schedule_summer.summer_id
	                      								LEFT JOIN ss_session_sections ON ss_session_sections.section_id = schedule_summer.summer_section_id
	                      								LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_session_sections.section_session_id
	                      								WHERE workday_staff_id = :staff_id AND event_type = 'X'
	                      								ORDER BY event_date");
								$stmt->bindValue(':staff_id', $staff_id);
								$stmt->execute();
								$workdays = $stmt->fetchAll();
								foreach ($workdays as $workday)
				                { ?>
				                  	<tr id="tr-s<?php print $workday['workday_id']; ?>">
										<td><i id="del-workday-<?php print $workday['workday_id']; ?>" class="fa fa-trash del-workday"></i></td>
										<td><?php print date("M d",strtotime($workday['event_date'])); ?></td>
			                     		<td style="text-align: left;"><?php print $workday['session_program_code'] . $workday['session_number']." - ".$workday['summer_description']; ?></td>
				                      	<td><?php print $workday['workday_percentage']; ?></td>
				                 	</tr>
				                 	<?php
				              	}
					          	?>
					        </table>
				        </form>
			    	</div>
		    	</div>
	      	</section>
	
	      	<!-- MONTH CALENDAR -->
	      	<section id="full-calendar" class="page">
		        <h1>Calendar</h1>
		        <div class="pure-g">
		          	<div id="calendar" class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3"></div>
		          	<div id="schedule" class="pure-u-1 pure-u-sm-1-2 pure-u-md-2-3"></div>
		        </div>
	      	</section>
	
		    <!-- PAYROLL -->
		    <section id="payroll" class="page">
		        <h1>Payroll</h1>
          		<div class="pure-g">
		            <div class="pure-u-1 pure-u-md-1-3">
		            	<h2>Pay Info</h2>
		            	<form class="pure-form pure-form-stacked"  method="post" action="">
			              	<div class="input-padding">
				                <label>Rate of Pay</label>
				                <input class="pure-input-1 gl-input" name="admin_rate_of_pay" type="text" onKeyPress="return noenter()" value="<?php print $staff['admin_rate_of_pay']; ?>">
			              	</div>			            
			              	<div class="input-padding">
				                <label>Rate of Pay Explanation</label>
				                <input class="pure-input-1 gl-input" name="admin_rate_of_pay_exp" type="text" onKeyPress="return noenter()" value="<?php print $staff['admin_rate_of_pay_exp']; ?>">
			              	</div>
			         	</form>
			         	<h2>Add Pay Deducation</h2>
			         	<form id="add-deduction-form" class="pure-form pure-form-stacked"  method="post" action="" style="text-align: center;">
			         		<input type="hidden" name="staff" value="<?php print $staff_id; ?>">			              	
			              	<div class="input-padding">
			              		<label>Deduction Payroll</label>
						        <select class="pure-input-1" name="deduction_date">
						          	<?php
									$findpayperiods = "SELECT * FROM staff_ss_payperiods
																			ORDER BY period_start";
									foreach ($conn->query($findpayperiods) as $row)
									{
										print "<option value={$row['period_end']}>".$row['period_start'];
										print " - ".$row['period_end']."</option>";
									}
			                  		?>
						        </select>
							</div>
						    <div class="input-padding">
								<label>Amount</label>
								<input class="pure-input-1" type="text" name="deduction_amount" />
							</div>
					        <button id="add-deduction-button" class="plaintext-button">add deduction</button>
			            </form>
			    	</div>
	        		<div class="pure-u-1 pure-u-md-2-3">
	        			<h2>Summer Payroll</h2>
			       	 	<table class="pure-table pure-table-bordered gl-staff-payroll">
			          		<tr>
					            <th>Pay Period Start</th>
					            <th>Pay Period End</th>
					            <th>Work Dates</th>
					            <th>Days Worked</th>
					            <th>Pay</th>
					            <th>Pay Date</th>
				          	</tr>
			          		<?php 
				          		$i = 0;
				            	$totalpay = 0;
				            	$totalpaydays = 0;
		
				             	$periodpay = 0;
				             	$periodpaydays = 0;
								
								$paydateold = "";
								$paydatenew = "";
								$paydatefirst = 1;
				
					         	//Get Pay Periods
					           	$payperiodquery = "SELECT * FROM staff_ss_payperiods ORDER BY period_start"; 
			              
					              //For Each Pay Period - Get Workdays
					              foreach ($conn->query($payperiodquery) as $payperiod)
					              {
					              		$paydatenew = $payperiod['period_paydate'];	
										if ($paydatefirst == 1) {$paydatefirst = 0;}
										elseif ($paydatenew != $paydateold) 
										{
											// Period Totals
											?>
											<tr class="periodtotal">
						              			<td class="periodtotal" colspan=3>Total for Pay Period</td>
						              			<td><?php print $periodpaydays; ?></td>
						             			<td>$<?php print number_format($periodpay, 2) ; ?></td>
						             			<td><?php print date("M d", strtotime($paydateold)); ?></td>
						             		</tr>
											<?php
											$periodpay = 0; 
											$periodpaydays = 0;
										}
											
					              		$workdate = $payperiod['period_start'];
										$end_day = $payperiod['period_end'];
										$workdaydays = "";
										$weekpaydays = 0;
										$weekpay = 0;
										while (strtotime($workdate) <= strtotime($end_day))		
										{
											$workdaytotal = 0;
											// Summer Program Workdays
						              		$stmt = $conn -> prepare("SELECT SUM(workday_percentage) AS workday_sum FROM staff_workdays
						              								LEFT JOIN schedule_events ON schedule_events.event_id = staff_workdays.workday_event_id
																	WHERE event_date = :event_date AND workday_staff_id = :staff_id");
							                $stmt -> bindValue(':event_date', $workdate);
							                $stmt -> bindValue(':staff_id', $staff['staff_id']);
							                $stmt -> execute();
							                $workday = $stmt -> fetch(PDO::FETCH_ASSOC);
											
											// Combine and Max Out Percentages
											$workdaytotal = $workday['workday_sum'];
											if ($workdaytotal > 1) {$workdaytotal = 1;}
											if ($workdaytotal > 0) {
												if ($workdaydays == "") {$workdaydays = date("M d", strtotime($workdate));}
												else {$workdaydays = $workdaydays.", ".date("M d", strtotime($workdate));}
											}
											$weekpaydays = $weekpaydays + $workdaytotal;		
											
											//Increment Date
											$workdate = date("Y-m-d", strtotime("+1 day", strtotime($workdate)));
										}
										$weekpay = $weekpaydays * $staff['admin_rate_of_pay'];
					                	?>
			                				
			            				<tr>
			            					<td width="15%"><?php print date("M d", strtotime($payperiod['period_start'])); ?></td>
			            					<td width="15%"><?php print date("M d", strtotime($payperiod['period_end'])); ?></td>
			            					<td width="25%"><?php print $workdaydays; ?></td>
			            					<td width="15%"><?php print $weekpaydays ; ?></td>
			            					<td width="15%">$<?php print number_format($weekpay, 2); ?></td>
			            					<td width="15%"><?php print date("M d", strtotime($paydatenew)); ?></td>
										</tr>
										
										<?php
					                	$periodpay = $periodpay + $weekpay;
						                $periodpaydays = $periodpaydays + $weekpaydays;
						                
						                $totalpay = $totalpay + $weekpay;
						                $totalpaydays = $totalpaydays + $weekpaydays;
							
						                //Calculate Deductions
						                $stmt = $conn -> prepare("SELECT * FROM staff_paydeductions
																WHERE deduction_date = :period_end AND deduction_staff_id = :staff_id");
						                $stmt -> bindValue(':period_end', $payperiod['period_end']);
						                $stmt -> bindValue(':staff_id', $staff['staff_id']);
						                $stmt -> execute();
						                $paydeductions = $stmt -> fetchAll();
						                foreach ($paydeductions as $paydeduction)
						                {
							               	if (isset($paydeduction))
											{?>
			               						<tr>
													<td colspan=4 style="text-align: right"><em>Staff Training Course Deduction&nbsp;</em></td>
			                						<td>-$<?php print number_format($paydeduction['deduction_amount'], 2) ; ?></td>
			                						<td style="background: #222;"></td>
												</tr>
												<?php
			                					$totalpay = $totalpay - $paydeduction['deduction_amount'];
						                   $periodpay = $periodpay - $paydeduction['deduction_amount'];
											}
			            				}
										$paydateold = $paydatenew;
									}?>
		
							<tr class="summertotal">
								<td class="summertotal" colspan=3>Summer Total</td>
				           		<td><?php print $totalpaydays; ?></td>
				            	<td>$<?php print number_format($totalpay, 2); ?></td>
				            	<td style="background: #000;"></td>
			            	</tr>
			     		</table>
			     	</div>
				</div>
	      	</section>
    		
    		<!-- Upload Docs -->
    		<section id="uploads" class="page">
    			<h1>Attached Files</h1>
    			<div class="pure-g">
    				<div class="pure-u-1 pure-u-sm-1-3">
    					<div style="padding: 10px">
	    					<h2>View Uploaded Files</h2>
	    					<div class="input-padding">
		    					<?php
		    					$stmt = $conn->prepare("SELECT * FROM staff_files WHERE file_staff_id = :file_staff_id ORDER BY file_title");
		    					$stmt->bindValue(':file_staff_id', $staff_id);
							   $stmt->execute();
							   $filelist = $stmt->fetchAll();
								foreach ($filelist as $file)
						    	{?>
						    		<a id="staff-file-<?php print $file['file_id']; ?>" style="display: inline;" class="file-list" href="docs/<?php print $file['file_name']; ?>" target="_blank">
						    			<i class="fa fa-file-pdf-o"></i>
						    			<?php print $file['file_title']." (".$file['file_date'].") "; ?>
						    		</a>
						    		<i style="color: #900;" id= "del-file-<?php print $file['file_id']; ?>" class="fa fa-times-circle del-file"></i>
						    		<br />
						    	<?php
								}
								?>
				    		</div>
				    	</div>
    				</div>
    				<div class="pure-u-1 pure-u-sm-2-3">
    					<div style="padding: 10px">
						 	<h2>Upload New File</h2>
						 	<form class="pure-form pure-form-stacked" action="process-upload-doc.php" method="POST" enctype="multipart/form-data">
								<input type="hidden" name="staff" value="<?php print $staff_id; ?>">			              	
								<div class="pure-g">
									<div class="pure-u-1 pure-u-lg-1-2">
										<label>Document Title</label>
										<input class="pure-input-1" type="text" name="file_title" required/>
									</div>
									<div class="pure-u-1 pure-u-lg-1-2">
										<label>File to Upload</label>
										<div class="pure-input-1 file-input">
											<input  class="pure-input-1" type="file" name="staff_file" required/>
										</div>
									</div>
									<div style="text-align: center;" class="pure-u-1">
										<button class="plaintext-button" type="submit"><i class="fa fa-upload"></i> upload file</button>
									</div>
								</div>
							</form>
						</div>
					</div>					
	    		</div>
    		</section>   		
   		</div>
  	</body>
</html>

<script>

	$(document).ready(function() {

		var hash = window.location.hash.substr(1);
		if (hash == "") {
			$("#profile").addClass("active");
			$("#profile").fadeIn('fast');
		}
		else {
			hash = "#" + hash;
			$.when($(".page").removeClass('active')).done(function () {
				$.when($('.page').fadeOut('fast')).done(function() {
					//alert(hash);
					$(hash).addClass("active");
					$(hash).fadeIn('fast');
					$('#main').scrollTop();
					if (hash == '#full-calendar')
						{
							$('#working').fadeIn('fast');
							$('#calendar').load('../calendar/cal-calendar.php?r=sa&sid=<?php print $staff_id; ?>', function() {
								$(hash).fadeIn('fast', function() {
									$('#working').fadeOut('fast');
								});
							});
						}
				});
			});
		}
		
		window.onhashchange = hash_page;
		
		function hash_page() {
			var hash = window.location.hash.substr(1);
			hash = "#" + hash;
			//alert(hash);
			if (hash != "") {
				if ($('#main-menu').hasClass("active")) {
					$('#main-menu').removeClass('active');
					$('#main-menu').animate({
						right : '-277px'
					}, 300);
				}
				$.when($(".page").removeClass('active')).done(function () {
					$.when($('.page').fadeOut('fast')).done(function() {
						$(hash).addClass("active");
						$(hash).fadeIn('fast');
						$('#main').scrollTop();
						if (hash == '#full-calendar')
						{
							$('#working').fadeIn('fast');
							$('#calendar').load('../calendar/cal-calendar.php?r=sa&sid=<?php print $staff_id; ?>', function() {
								$(hash).fadeIn('fast', function() {
									$('#working').fadeOut('fast');
								});
							});
						}
					});
				});
			}
		}
		
		//SHOW STATUS ON ENTRY
		$("#staff_status").load("staff-status.php",{'staff': "<?php print $staff_id ?>"},function() {$("#staff_status").fadeIn("fast");});
		
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
		});

		// MENU ACTION
		$('.gl-menu-item').click(function() {
			id = $(this).attr("id");
			id = id.replace("-button", "");
			id = "#" + id;			
			$('#main-menu').removeClass('active');
			$('#main-menu').animate({
				right : '-277px'
			}, 300);
		});

		// PROCESS FORM INPUT
		$('.gl-input').change(function(e) {
			name = $(this).attr("name");
			val = $(this).val();
			$(this).css('background', '#F7F7F7');
			//alert(name+" "+val); return false;
			$.ajax({
				type : "POST",
				url : "process-input.php",
				data : {
					name : name,
					val : val,
					staff : <?php print $staff_id; ?>
				},
				success : function() {
					if (name == "admin_rate_of_pay") {location.reload('true');}
					else 
					{
						$('.gl-input').css('background', 'transparent');
						$("#staff_status").load("staff-status.php",{'staff': "<?php print $staff_id ?>"},function() {$("#staff_status").fadeIn("fast");});
						if (name == "staff_name_last") {$('#last-name').replaceWith(val + ",");}
						if (name == "staff_name_common") {$('#first-name').replaceWith(val);}
					}
				}
			});
		});
		
		$('.gl-check').change(function(e) {
			name = $(this).attr("name");
			if ($(this).is(':checked')) {val = 1;}
			else {val = 0;}
			//alert(name + val);
			$(this).css('background', '#F7F7F7');
			$.ajax({
				type : "POST",
				url : "process-input.php",
				data : {
					name : name,
					val : val,
					staff : <?php print $staff_id; ?>
				},
				success : function() {
					$('.gl-input').css('background', 'transparent');
					$("#staff_status").load("staff-status.php",{'staff': "<?php print $staff_id ?>"},function() {$("#staff_status").fadeIn("fast");});
				}
			});
		});
		
		// ADD-WORKDAY ACTION
		$('#add-workday-session').change(function() {
			$("#working").show();
			val = $(this).val();
			staff = <?php print $staff_id; ?>;
			//alert(val);return false;		
			$.ajax({
				type : "POST",
				url : "process-add-workday-session.php",
				data : {
					val : val, staff : staff
				},
				success : function() {				
					location.reload('true');
				}
			});
		});
		
		// ADD-DEDUCTION ACTION
		$('#add-deduction-button').click(function() {
			$("#add-deduction-form").submit(function(e){
	   			e.preventDefault();
	 			$("#working").show();
	   			var senddata = $(this).serialize();
				//alert(senddata);return false;		
				$.ajax({
					type : "POST",
					url : "process-add-deduction.php",
					data: senddata,
		      		success: function() {location.reload('true');}
				});
			});
		});
		
		
		// DEL-WORKDAY ACTION
		$('.del-workday').click(function() {
			$("#working").show();
			id = $(this).attr("id");
			id = id.replace("del-workday-", "");
			//alert(id);	
			trid = "#tr-s"+id;	
			staff = <?php print $staff_id; ?>;
			//alert(staff);return false;
			$.ajax({
				type : "POST",
				url : "process-del-workday.php",
				data : {
					val : id, staff : staff
				},
				success : function() {
					$("#working").hide();
					$(trid).hide();
				}
			});
		});
		
		// DEL-ALL-WORKDAY ACTION
		$('.del-all').click(function() {
			if (confirm("Are you sure you want to delete all <?php print $staff['staff_name_common']; ?>'s summer workdays?") == true)
			{
				$("#working").show();
				staff = <?php print $staff_id; ?>;
				$.ajax({
					type : "POST",
					url : "process-del-workday-all.php",
					data : {
						staff : staff
					},
					success : function() {
						location.reload('true');
					}
				});
			}
		});
		
		$('.del-file').click(function(e) {
			id = $(this).attr("id");
			val = id.replace("del-file-", "");
			id = "#staff-file-" + val;
			id2 = "#del-file-" + val;
			//alert(val);alert(id);return false;
			$.ajax({
				type : "POST",
				url : "process-del-file.php",
				data : {
					val : val
				},
				success : function() {
					$(id).hide();
					$(id2).hide();
				}
			});
		});
		
		$(".textarea_expand").on("keyup", function() {
			this.style.height = "1px";
			this.style.height = (this.scrollHeight) + "px";
		});
		
		$('#back-button').click(function() {
			window.location = "../staff-admin/index.php";
		});
		
		$('#logout-button').click(function() {
			window.location = "../shared/logout.php";
		});
		
	});
	
</script>