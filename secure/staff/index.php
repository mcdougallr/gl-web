  <?php
	if(!isset($_SESSION)) {
	     session_start();
	}

  	$_SESSION['refer'] = "s";

  	include ('../shared/dbconnect.php');
  	include ('../shared/functions.php');
  	include ('../shared/authenticate.php');

  	$stmt = $conn -> prepare("SELECT aff_id FROM gl_affirmations");
  	$stmt -> execute();
  	$affs = $stmt -> fetchAll();
  	$aff_num = rand(0, count($affs));

  	$stmt = $conn -> prepare("SELECT * FROM gl_affirmations 
													WHERE aff_id = :aff_id");
  	$stmt -> bindValue(':aff_id', $aff_num);
  	$stmt -> execute();
  	$aff = $stmt -> fetch(PDO::FETCH_ASSOC);

  	if (isset($_SESSION['gl_staff_id']))
  	{
    	$stmt = $conn -> prepare("SELECT * FROM staff 
													WHERE staff_id = :staff_id");
    	$stmt -> bindValue(':staff_id', $_SESSION['gl_staff_id']);
    	$stmt -> execute();
    	$staff = $stmt -> fetch(PDO::FETCH_ASSOC);
  	}
  	else
  	{$staff = "";}

  	$staffaccess['staff_access'] = $staff['staff_access'];
  	
  	if (!isset($_SESSION['date'])) {$_SESSION['date'] = date("Y-m-d");}
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
	    <link rel="stylesheet" href="_gl-staff.css">
	    <link href="custom-theme/jquery-ui.css" rel="stylesheet" />
	    <script src="../../scripts/jquery/jquery-1.11.0.min.js"></script>
	    <script src="../shared/jquery-ui-1.10.3.custom.js"></script>
	    
	    <title><?php print $staff['staff_name_common']; ?>'s Staff Page</title>

  	</head>
  	<body>
	    <div id="working"><img id="loading" src="../shared/loading.gif"/></div>
	    <nav id="main-menu" class="pure-u-1 nav-menu right-menu reg">
	      	<ul>
		        <li><a id="profile-button" href="#profile" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Profile</a></li>
		        <li><a id="admin-button" href="#admin" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Admin</a></li>
		        <li><a id="emerg-button" href="#emerg" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Emerg Info</a></li>
		        <li><a id="day-calendar-button" href="#day-calendar" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Calendar</a></li>
		       <li><a id="payroll-button" href="#payroll" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Payroll</a></li>
		        <li><a id="contract-button" href="#contract" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Contract</a></li>
		        <li><a id="staffing-button" href="#staffing" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Staffing</a></li>
		        <li><a id="resources-button" href="#resources" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Resources</a></li>
		        <li><a id="pw-button" href="#pw" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Password</a></li>
	      	</ul>
	      	<div style="height: 50px;"></div>
    	</nav>
    	
    	<!-- INCLUDE DB MENU -->
    	<?php if ($staffaccess['staff_access'] > 1){ include ('../shared/dbmenu.php');}?>
    	
    	<!-- HEADER -->
    	<div id="header">
      		<div class="pure-g">
        		<div class="pure-u-3-4" style="text-align: left;height: 0;">
          			<div id="header-left">
            			<img id="db-menu-button" src="../shared/sunnyblack.png" class="menu-button" title="Database Menu">
            			<div id="top-text">
              				Hey <?php print $staff['staff_name_common'] . ", " . $aff['aff_text']; ?>
            			</div>
            			<div id="top-text-small">
              				Hey <?php print $staff['staff_name_common']; ?>, have a great day!
            			</div>
          			</div>
        		</div>
        		<div class="pure-u-1-4" style="text-align: right;">
          			<div id="header-right">
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
					                <input class="pure-input-1 gl_input" name="staff_name_last" type="text" onconkeypress="return noenter()" placeholder="Last Name" value="<?php print $staff['staff_name_last']; ?>">
				             	</div>
			            	</div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
					            <div class="input-padding">
						              <label>Formal First Name</label>
						              <input class="pure-input-1 gl_input" name="staff_name_first" type="text" onkeypress="return noenter()" placeholder="Formal First Name" value="<?php print $staff['staff_name_first']; ?>">
					            </div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				              	<div class="input-padding">
				                	<label>Common First Name</label>
				                	<input class="pure-input-1 gl_input" name="staff_name_common" type="text" onkeypress="return noenter()" placeholder="Common First Name" value="<?php print $staff['staff_name_common']; ?>">
				              	</div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				              	<div class="input-padding">
				                	<label>Email</label>
				                	<input class="pure-input-1 gl_input" name="staff_email" type="text" onkeypress="return noenter()" placeholder="Email" value="<?php print $staff['staff_email']; ?>">
				              	</div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				              	<div class="input-padding">
					                <label>Home Phone</label>
					                <input class="pure-input-1 gl_input" name="staff_phone_home" type="tel" pattern="\d\d\d\-\d\d\d\-\d\d\d\d" onkeypress="return noenter()" value="<?php print $staff['staff_phone_home']; ?>">
				             	</div>
		            		</div>
		           			<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
		              			<div class="input-padding">
					                <label>Cell Phone</label>
					                <input class="pure-input-1 gl_input" name="staff_phone_cell" type="tel" pattern="\d\d\d\-\d\d\d\-\d\d\d\d" onkeypress="return noenter()" value="<?php print $staff['staff_phone_cell']; ?>" title="###-###-####">
				              	</div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				              	<div class="input-padding">
					                <label>Date of Birth</label>
					                <input id="staff_dob" style="-webkit-appearance: none;" class="pure-input-1 gl-main-date gl_input" type="date" name="staff_dob" onkeypress="return noenter()" value="<?php print $staff['staff_dob']; ?>">
				              	</div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				              	<div class="input-padding">
					                <label>Sex</label>
					                <select style="-webkit-appearance: none;" class="pure-input-1 gl_input" onkeypress="return noenter()" name="staff_Sex" placeholder="Sex">
				                  		<option value="Female" <?php if ($staff['staff_sex'] == "Female"){print " selected";} ?>>Female</option>
				                  		<option value="Male" <?php if ($staff['staff_sex'] == "Male"){print " selected";} ?>>Male</option>
				                	</select>
				              	</div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				              	<div class="input-padding">
					                <label>T-Shirt Size (Unisex)</label>
					                <input class="pure-input-1 gl_input" name="staff_shirtsize" type="text" onkeypress="return noenter()" placeholder="T-Shirt Size" value="<?php print $staff['staff_shirtsize']; ?>">
				              	</div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
					              <div class="input-padding">
						              <label>Street</label>
						              <input class="pure-input-1 gl_input" name="staff_p_address" type="text" onkeypress="return noenter()" placeholder="Address" value="<?php print $staff['staff_p_address']; ?>">
					              </div>
				            </div>
				            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
				              	<div class="input-padding">
				                	<label>City</label>
				                	<input class="pure-input-1 gl_input" name="staff_p_city" type="text" onkeypress="return noenter()" placeholder="Address" value="<?php print $staff['staff_p_city']; ?>">
				              	</div>
				            </div>
				            <div class="pure-u-1-2 pure-u-sm-1-4 pure-u-md-1-6 pure-u-lg-1-8">
				              	<div class="input-padding">
				                	<label>Province</label>
				                	<input class="pure-input-1 gl_input" name="staff_p_province" type="text" onkeypress="return noenter()" placeholder="Address" value="<?php print $staff['staff_p_province']; ?>">
				              	</div>
				            </div>
				            <div class="pure-u-1-2 pure-u-sm-1-4 pure-u-md-1-6 pure-u-lg-1-8">
				              	<div class="input-padding">
				                	<label>Postal Code</label>
				                	<input class="pure-input-1 gl_input" name="staff_p_postalcode" type="text" onkeypress="return noenter()" placeholder="Address" value="<?php print $staff['staff_p_postalcode']; ?>">
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
				                <textarea class="pure-input-1 gl-input textarea_expand" id="admin_notes" name="admin_notes" readonly><?php print $staff['admin_notes']; ?></textarea>
			              	</div>
			              	<hr />
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4 adminchecklist">
			              	<div class="input-padding">
			            		<div class="checkstyle"> 
	          						<input name="admin_summer_confirmed" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_summer_confirmed'] == 1){print " checked";} ?> disabled>
						          	<label>Summer Confirmed</label>
						        </div>
						        <div class="checkstyle"> 
	          						<input name="admin_contract" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_contract'] == 1){print " checked";} ?> disabled>
						          	<label>Contract</label>
						        </div>
						  	</div>
						</div>
						<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4 adminchecklist">
			              	<div class="input-padding">
			            		<div class="checkstyle"> 
	          						<input name="admin_payroll_form" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_payroll_form'] == 1){print " checked";} ?> disabled>
						          	<label>Payroll Info</label>
						        </div>
						        <div class="checkstyle"> 
	          						<input name="admin_deposit" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_deposit'] == 1){print " checked";} ?> disabled>
              						<label>Bank Info</label>
						        </div>
						  	</div>
						</div>
						<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4 adminchecklist">
			              	<div class="input-padding">
			            		<div class="checkstyle"> 
	          						<input name="admin_tax" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_tax'] == 1){print " checked";} ?> disabled>
              						<label>Tax Forms</label>
						        </div>
						        <div class="checkstyle"> 
	          						<input name="admin_policy_chat" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_policy_chat'] == 1){print " checked";} ?> disabled>
             						<label>Policy Chat</label>
						        </div>
						  	</div>
						</div>
						<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4 adminchecklist">
			              	<div class="input-padding">
			            		<div class="checkstyle"> 
	          						<input name="admin_CPIC" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_CPIC'] == 1){print " checked";} ?> disabled>
						          	<label>CPIC on File</label>
						        </div>
						        <div class="checkstyle"> 
	          						<input name="admin_OSHA" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($staff['admin_OSHA'] == 1){print " checked";} ?> disabled>
              						<label>OSHA</label>
						        </div>
						  	</div>
						</div>
						<div class="pure-u-1"><hr /></div>
			            <div class="pure-u-1 pure-u-sm-1-3">
			              	<div class="input-padding">
				                <label>Staff Type</label>
				                <select class="pure-input-1" onkeypress="return noenter()" name="admin_stafftype" readonly="readonly">
			                  		<option value="Teacher" <?php if ($staff['admin_stafftype'] == "Field Staff BEd"){print " selected";} ?> disabled>Field Staff BEd</option>
			                  		<option value="Trip Staff" <?php if ($staff['admin_stafftype'] == "Field Staff"){print " selected";} ?> disabled>Field Staff</option>
			                	</select>
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-3">
			              	<div class="input-padding">
				                <label for="admin_yearhired">Year Hired</label>
				                <input class="pure-input-1" id="admin_yearhired" name="admin_yearhired" onkeypress="return noenter()" type="text" value="<?php print $staff['admin_yearhired']; ?>" readonly>
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-3">
			              	<div class="input-padding">
				                <label>LDSB #</label>
				                <input class="pure-input-1 gl-input" id="admin_LDSBnum" name="admin_LDSBnum" onkeypress="return noenter()" type="text" value="<?php print $staff['admin_LDSBnum']; ?>" readonly>
			              	</div>
			            </div>	
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
			              	<div class="input-padding">
				                <label for="cert_swim">Swim Certification</label>
				                <input class="pure-input-1" id="cert_swim" name="cert_swim_num" onkeypress="return noenter()" type="text" value="<?php print $staff['cert_swim_num']; ?>" readonly>
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
			              	<div class="input-padding">
				                <label for="cert_swim_exp">Expiry Date</label>
				                <input style="-webkit-appearance: none;" class="pure-input-1" id="cert_swim_exp" name="cert_swim_exp" onkeypress="return noenter()" type="text" value="<?php print $staff['cert_swim_exp']; ?>" readonly>
			              	</div>
	            		</div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
			              	<div class="input-padding">
				                <label for="cert_FA">First-Aid Cert</label>
				                <input class="pure-input-1" id="cert_FA" name="cert_FA" onkeypress="return noenter()" type="text" value="<?php print $staff['cert_FA']; ?>" readonly>
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
			              	<div class="input-padding">
				                <label for="cert_FA_date">First Aid Certification Date</label>
				                <input style="-webkit-appearance: none;" class="pure-input-1" id="cert_FA_date" name="cert_FA_date" onkeypress="return noenter()" type="text" value="<?php print $staff['cert_FA_date']; ?>" readonly>
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
				                <input class="pure-input-1 gl_input" name="staff_econtact_name_last" type="text" placeholder="Last Name" value="<?php print $staff['staff_econtact_name_last']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
			              	<div class="input-padding">
				                <label>Emergency Contact First Name</label>
				                <input class="pure-input-1 gl_input" name="staff_econtact_name_first" type="text" placeholder="First Name" value="<?php print $staff['staff_econtact_name_first']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
			              	<div class="input-padding">
				                <label>Emergency Contact Relationship</label>
				                <input class="pure-input-1 gl_input" name="staff_econtact_relationship" type="text" onKeyPress="return noenter()" value="<?php print $staff['staff_econtact_relationship']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
			              	<div class="input-padding">
				                <label>Emergency Contact Phone #1</label>
				                <input class="pure-input-1 gl_input" name="staff_econtact_phone_day" type="tel" onkeypress="return noenter()" value="<?php print $staff['staff_econtact_phone_day']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
			              	<div class="input-padding">
				                <label>Emergency Contact Phone #2</label>
				                <input class="pure-input-1 gl_input" name="staff_econtact_phone_evening" type="tel" onkeypress="return noenter()" value="<?php print $staff['staff_econtact_phone_evening']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
			              	<div class="input-padding">
				                <label>Health Card Number</label>
				                <input class="pure-u-1 gl_input" name="staff_health_card" type="text" onKeyPress="return noenter()" value="<?php print $staff['staff_health_card']; ?>">
			              	</div>
			            </div>
			            <div class="pure-u-1">
			              	<div class="input-padding">
				                <label>Health Conditions</label>
				                <textarea class="pure-input-1 textarea_expand gl_input" name="staff_health_conditions"><?php print $staff['staff_health_conditions']; ?></textarea>
			              	</div>
			            </div>
			            <div class="pure-u-1">
			              	<div class="input-padding">
				                <label>Recent Injuries/Operations</label>
				                <textarea class="pure-input-1 textarea_expand gl_input" name="staff_health_injuries"><?php print $staff['staff_health_injuries']; ?></textarea>
			              	</div>
			            </div>
			            <div class="pure-u-1">
			              	<div class="input-padding">
				                <label>Dietary Restrictions</label>
				                <textarea class="pure-input-1 textarea_expand gl_input" name="staff_health_dietary"><?php print $staff['staff_health_dietary']; ?></textarea>
			              	</div>
			            </div>
		          	</div>
		        </form>
			</section>
	
	      	<!-- DAY CALENDAR -->
	      	<section id="day-calendar" class="page">
	        	<h1>Calendar</h1>
		        <div class="pure-g">
		          	<div id="calendar" class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3"></div>
		          	<div id="schedule" class="pure-u-1 pure-u-sm-1-2 pure-u-md-2-3"></div>
		        </div>
	      	</section>
	
		    <!-- PAYROLL -->
		    <section id="payroll" class="page">
		        <h1>Payroll</h1>
		        <form class="pure-form pure-form-stacked"  method="post" action="">
	          		<div class="pure-g">
			            <div class="pure-u-1 pure-u-sm-1-2">
			              	<div class="input-padding">
				                <label>Rate of Pay</label>
				                <input class="pure-input-1" name="admin_rate_of_pay" type="text" onKeyPress="return noenter()" value="<?php print $staff['admin_rate_of_pay']; ?>" readonly>
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2">
			              	<div class="input-padding">
				                <label>Rate of Pay Explanation</label>
				                <input class="pure-input-1" name="admin_rate_of_pay_exp" type="text" onKeyPress="return noenter()" value="<?php print $staff['admin_rate_of_pay_exp']; ?>" readonly>
			              	</div>
			            </div>
			    	</div>
	        	</form>
	        	<hr />
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
	      	</section>
	
	      	<!-- CONTRACT -->
	      	<section id="contract" class="page">
		        <h1> Paperwork</h1>
		        <form method=post class="pure-form pure-form-stacked" method="post" action="">
	          		<div class="center">
			        	<p>
			        		<strong><em>All Staff:  </em></strong>Please print, sign and date your work agreement.
			            </p>
			           	<a id="contract_print_button" href="contract.php">Print Work Agreement <i class="fa fa-print"></i></a>
			            <hr />
			    	</div>
	            	<div class="center">
	              		<p>
	                		<strong><em>New Staff:  </em></strong>You must obtain a <em>CPIC</em> and include it with your contract.<br />
	                		<strong><em>Returning Staff:  </em></strong>Please include a completed copy of the <em>Offence Declaration</em> with your contract.
	              		</p>
	              		<a id="offdec_button" href="offdec.pdf">Print Offence Declaration <i class="fa fa-print"></i></a>
		              	<hr />
		        	</div>
	            	<div class="center">
	              		<p>
	                		<strong><em>New Staff:  </em></strong>Complete the form below and include it with your contract.<br />
	                		<strong><em>Returning Staff:  </em></strong>Complete the form below <em>only if your payroll or banking information has changed</em>.
	              		</p>
	              		<a id="payroll_info_button" href="NewEmp.pdf">Print LDSB Payroll Form <i class="fa fa-print"></i></a>
		              	<hr />
	            	</div>
	            	<div class="center">
	              		<p>
	                		<strong><em>All Staff:  </em></strong>Complete if you require anything other than the basic tax exemption, please attach a completed copy of these forms with your contract.
	              		</p>
	              		<a id="fedtax_button" href="https://www.canada.ca/content/dam/cra-arc/formspubs/pbg/td1/td1-fill-18e.pdf" target="_blank">Print Federal Tax Form <i class="fa fa-long-arrow-right"></i></a><br />
	              		<a id="ontax_button" href="https://www.canada.ca/content/dam/cra-arc/formspubs/pbg/td1on/td1on-fill-18e.pdf" target="_blank">Print Provincial Tax Form <i class="fa fa-long-arrow-right"></i></a>
	              		<hr />
	            	</div>
	            	<div class="center">
	              		<p>
	                		<strong><em>All Staff:  </em></strong>If you have not done so already, complete the health and safety awareness training and submit the certification provided at the end of the training. <br />
	                		If you have done the training with another job, simply submit the certificate. 
	              		</p>
	              		<a id="hs_button" href="http://www.labour.gov.on.ca/english/hs/elearn/worker/index.php">eLearning Module <i class="fa fa-long-arrow-right"></i></a><br />
	              		<hr />
	            	</div>
	        	</form>
	      	</section>
	
	      	<!-- STAFFING -->
	      	<section id="staffing" class="page">
	      		<div style="padding: 10px;"><?php include ("../staff-admin/session-staff.php"); ?></div>
	      	</section>
	
	  		<!-- RESOURCES -->
	      	<section id="resources" class="page">
		        <h1>Resources</h1>
		        <div class="pure-g">
				   	<div class="pure-u-1 pure-u-md-1-2" style="text-align: center;">	
				      <div style="padding: 10px;font-family: FTYS;">Your Files</div>
				      <hr style="width: 90%">
				      <?php
		         		$stmt = $conn -> prepare("SELECT * FROM staff_files
																	WHERE file_staff_id = :staff_id
																	ORDER BY file_date");
	                $stmt -> bindValue(':staff_id', $staff['staff_id']);
	                $stmt -> execute();
	                $staff_files = $stmt -> fetchAll();
		         		foreach ($staff_files as $file)
				        {
				        	print "<a href=\"../staff-edit/docs/".$file['file_name']."\" target=\"_blank\">".$file['file_title']." (".$file['file_date'].")</a>";
						};
					?>
				    </div>
				    <div class="pure-u-1 pure-u-md-1-2" style="text-align: center;">	
				      <div style="padding: 10px;font-family: FTYS">Manuals</div>
				      <hr style="width: 90%">
				     <!-- <a href="/staff/manuals/barn-oe.pdf" target="_blank">OE Staff</a>-->
				      <a href="/staff/manuals/s_gap_17.pdf" target="_blank">GAP Student</a>
				      <a href="/staff/manuals/s_q_17.pdf" target="_blank">Q Student</a>
				      <a href="/staff/manuals/t_qgap_17.pdf" target="_blank">Q/GAP Trip</a>
				      <a href="/staff/manuals/s_or_17.pdf" target="_blank">OR Student</a>
				      <a href="/staff/manuals/t_or_17.pdf" target="_blank">OR Trip</a>
				      <a href="/staff/manuals/s_or1.5_17.pdf" target="_blank">OR1.5 Student</a>
				      <a href="/staff/manuals/t_or1.5_17.pdf" target="_blank">OR1.5 Trip</a>
				      <a href="/staff/manuals/s_op_17.pdf" target="_blank">OP Student</a>
				      <a href="/staff/manuals/t_op_17.pdf" target="_blank">OP Trip</a>
				      <a href="/staff/manuals/s_os_17.pdf" target="_blank">OS Student</a>
				      <a href="/staff/manuals/t_os_17.pdf" target="_blank">OS Trip</a>
				      <a href="/staff/manuals/s_wic_17.pdf" target="_blank">WIC Student - River</a>
				      <a href="/staff/manuals/s_wic_p_17.pdf" target="_blank">WIC Student - Placement</a>
				      <a href="/staff/manuals/t_wic_17.pdf" target="_blank">WIC Trip</a>
				      <a href="/staff/manuals/s_lt_17.pdf" target="_blank">LT Student</a>
				      <a href="/staff/manuals/t_lt_17.pdf" target="_blank">LT Trip</a>
				      <a href="/staff/manuals/s_kic_17.pdf" target="_blank">KIC Student</a>
				      <a href="/staff/manuals/t_kic_17.pdf" target="_blank">KIC Trip</a>
				      <a href="/staff/manuals/staff_17.pdf" target="_blank">Staff</a>
				      <div style="padding: 10px;font-family: FTYS">Other</div>
				      <hr style="width: 90%">
				      <a href="http://www.limestone.on.ca/lesaa/General/Concussion%20Protocal/C1-Concussion%20Management%20Procedures%20Return%20to%20Learn%20and%20Ret.pdf" target="_blank">LDSB Concussion Protocol</a>				    
				      <a href="http://www.limestone.on.ca/lesaa/General/Concussion%20Protocal/C4-Documentation%20for%20Diagnosed%20Concussion%20-%20salmon%20updated.pdf" target="_blank">LDSB Concussion Protocol - Paperwork</a>				    
					</div>
	        </section>
	
	      	<!-- PASSWORD -->
	      	<section id="pw" class="page">
		        <h1>Change Password</h1>
		        <form id="pw_form" method=post class="pure-form pure-form-stacked" method="post" action="">
	          		<div class="pure-g">
			            <div class="pure-u-1 pure-u-sm-1-2">
			              	<div class="input-padding">
				                <label>New Password</label>
				                <input class="pure-input-1" name="password" type="password" placeholder="New Password">
			              	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-2">
			              	<div class="input-padding">
				                <label>Confirm Password</label>
				                <input class="pure-input-1" name="confirm_password" type="password" placeholder="Confirm Password">
			              	</div>
			            </div>
	            		<div class="pure-u-1">
	              			<div class="input-padding center">
	                			<button id="submit-password" class="submit-button"><i class="fa fa-check"></i>change password</button>
	              		</div>
	            	</div>
	        	</form>
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
					if (hash == '#day-calendar')
					{
						$('#working').fadeIn('fast');
						$('#calendar').load('../calendar/cal-calendar.php?r=s', function() {
							$('#schedule').load('../schedule/cal-schedule-s.php?sid=<?php print $_SESSION['gl_staff_id'];?>', function() {
								$(hash).fadeIn('fast', function() {
									$('#working').fadeOut('fast');
								});
							});
						});
					}
				});
			});
		}
		
		window.onhashchange = hash_page;
		
		function hash_page() {
			// MAKE PROFILE ACTIVE ON ENTRY
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
						if (hash == '#day-calendar')
						{
							$('#working').fadeIn('fast');
							$('#calendar').load('../calendar/cal-calendar.php?r=s', function() {
								$('#schedule').load('../schedule/cal-schedule-s.php?sid=<?php print $_SESSION['gl_staff_id'];?>', function() {
									$(hash).fadeIn('fast', function() {
										$('#working').fadeOut('fast');
									});
								});
							});
						}
					});
				});
			}
		}

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

		// PROCESS FORM INPUT
		$('.gl_input').change(function(e) {
			name = $(this).attr("name");
			val = $(this).val();
			$(this).css('background', '#39F');

			$.ajax({
				type : "POST",
				url : "process-input.php",
				data : {
					name : name,
					val : val
				},
				success : function() {
					$('.gl_input').delay(300)
				    .queue(function() {
				        $('.gl_input').css('background', 'transparent').dequeue();
				    });
				}
			});
		});

		// PROCESS PASSWORD INPUT
		$('#pw_form').submit(function(b) {
			b.preventDefault();
			if ($(this).find('input[name=password]').val() != $(this).find('input[name=confirm_password]').val()) {
				alert("New Password and Confirm Password do not match. Password not saved.");
				return false;
			} else if ($(this).find('input[name=password]').val() == "") {
				alert("Password cannot be empty.");
				return false;
			} else {
				var senddata = $(this).serialize();
				//alert (senddata);return false;
				$.ajax({
					type : "POST",
					url : "process-pw.php",
					data : senddata,
					success : function() {
						alert("Password has been changed.  Please be sure to remember your new password for the next time you login!");
						window.location = "index.php";
						return false;
					}
				});
			}
		});

		$(".textarea_expand").on("keyup", function() {
			this.style.height = "1px";
			this.style.height = (this.scrollHeight) + "px";
		});
		
		$('#logout-button').click(function() {
			window.location = "../shared/logout.php";
		});

	}); 
</script>
