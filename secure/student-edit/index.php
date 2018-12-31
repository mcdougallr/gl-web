<?php
	if(!isset($_SESSION)) {
	     session_start();
	}

  	$_SESSION['refer'] = "sta";

  	include ('../shared/dbconnect.php');
  	include ('../shared/clean.php');
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
  	
  	if (isset($_GET['sid'])) {$_SESSION['student_id'] = cleantext($_GET['sid']);$student_id = cleantext($_GET['sid']);}
	else {header("Location: ../student-admin/index.php");}
	
	$stmt = $conn -> prepare("SELECT * FROM ss_registrations WHERE registration_id = :registration_id");
	$stmt -> bindValue(':registration_id', $_SESSION['student_id']);
	$stmt -> execute();
	$student = $stmt -> fetch(PDO::FETCH_ASSOC);
	if ($student == ""){header("Location: ../student-admin/index.php");}

	$phone_types = array("Home", "Cell", "Work", "Other");

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
	    <link rel="stylesheet" href="_gl-student-edit.css">
	    <link href="custom-theme/jquery-ui.css" rel="stylesheet" />
	    <script src="../../scripts/jquery/jquery-1.11.0.min.js"></script>
	    <script src="../shared/jquery-ui-1.10.3.custom.js"></script>
	    
	    <title>GL - <?php print $student['student_name_common']." ".$student['student_name_last'];?></title>

  	</head>
  	<body>
	    <div id="working"><img id="loading" src="../shared/loading.gif"/></div>
    	<!-- Main Menu Navigation -->
	    <nav id="main-menu" class="nav-menu right-menu reg">
	      	<li><a id="profile-button" href="#profile" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Profile</a></li>
	        <li><a id="admin-button" href="#admin" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Admin</a></li>
	        <li><a id="course-button" href="#course" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Course</a></li>
	        <li><a id="contacts-button" href="#contacts" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Contacts</a></li>
	        <li><a id="medical-button" href="#medical" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Medical</a></li>
	        <li><a id="additional-button" href="#additional" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Additional</a></li>
	        <li><a id="assessment-button" href="#assessment" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Assessment</a></li>
	        <li><a id="uploads-button" href="#uploads" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>File Uploads</a></li>
	        <li style="background-color: #C03030"><a id="email-confirm-button" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Email Confirmation</a></li>
	        <li style="background-color: #C03030"><a id="email-forms-button" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Email Forms Package</a></li>
	        <li style="background-color: #C03030"><a id="duplicate-button" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Duplicate Registration</a></li>
	        <li style="background-color: #C03030"><a id="delete-button" class="gl-menu-item"><i class="fa fa-arrow-circle-right"></i>Delete Registration</a></li>
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
            				<div id="last-name" style="display: inline-block"><?php print $student['student_name_last'].", "; ?></div>
            				<div id="first-name" style="display: inline-block"><?php print $student['student_name_common']; ?></div>
            				<br />
            				<div id="student_status" style="font-size: .8em;margin-top: -2px;"></div>
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
			    <h1>Student Profile</h1>
			    <form id="profile_form" class="pure-form pure-form-stacked" method="post" action="">
			      	<div class="pure-g"> 
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding"> 
					        	<label>Last Name</label>
					          	<input class="pure-input-1 gl-input" name="student_name_last" type="text" onkeypress="return noenter()" placeholder="Last Name" value="<?php print $student['student_name_last']; ?>">
			        		</div>
			        	</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding">  
					        	<label>Legal First Name</label>
					        	<input class="pure-input-1 gl-input" name="student_name_first" type="text" onkeypress="return noenter()" placeholder="First Name" value="<?php print $student['student_name_first']; ?>">
			        		</div>
			        	</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding">
			          			<label>Common First Name</label>
			          			<input class="pure-input-1 gl-input" name="student_name_common" type="text" onkeypress="return noenter()" placeholder="First Name" value="<?php print $student['student_name_common']; ?>">
			        		</div>	      
			        	</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding"> 
			          			<label>Contact Email</label>
			          			<input class="pure-input-1 gl-input" name="contact_email" type="text" onkeypress="return noenter()" placeholder="Contact Email" value="<?php print $student['contact_email']; ?>">
			        		</div>
			        </div>
						<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding"> 
			          			<label>Student Email</label>
			          			<input class="pure-input-1 gl-input" name="student_email" type="text" onkeypress="return noenter()" placeholder="Student Email" value="<?php print $student['student_email']; ?>">
			        		</div>
			        </div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding">  
			          			<label>Date of Birth</label>
			          			<input id="student_dob gl-input" class="pure-input-1 gl-main-date gl-input" type="date" name="student_dob" onkeypress="return noenter()" value="<?php print $student['student_dob']; ?>">
			        		</div>
			        	</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding">  
			          			<label>Sex</label>
					        	<select class="pure-input-1 gl-input" onkeypress="return noenter()" name="student_sex" placeholder="Sex">
					            	<option value="Female" <?php	if($student['student_sex']=="Female"){print " selected";} ?>>Female</option>
					            	<option value="Male" <?php	if($student['student_sex']=="Male"){print " selected";} ?>>Male</option>
					          	</select>
			        		</div>
			        	</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding">  
			          			<label>T-Shirt Size</label>
			          			<select class="pure-input-1 gl-input" name="student_shirtsize" onkeypress="return noenter()">
							        <option value="">Select...</option>
							        <?php
							        $shirtsizes = array("S" => "Adult Small", "M" => "Adult Medium", "L" => "Adult Large", "XL" => "Adult Extra Large");
							
							        foreach ($shirtsizes as $code => $text) {
							          print "<option value=" . $code;
							          if ($student['student_shirtsize'] == $code) {print " selected";}
							          print ">" . $text . "</option>";
							        }
							        ?>
							    </select>
			        		</div>
			        	</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding"> 
				          		<label>Grade</label>
						      	<select class="pure-input-1 gl-input" name="student_grade" onkeypress="return noenter()">
							        <option value="">Select...</option><?php
							        for ($i = 6; $i < 14; $i++) {
							          print "<option value=" . $i;
							          if ($student['student_grade'] == $i) {print " selected";}
							          print ">" . $i . "</option>";
							        }
							        ?>
								</select>
						    </div>				        
				        </div>
				        <div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding">  
			          			<label>OEN</label>
			          			<input class="pure-input-1 gl-input" name="student_oen" type="text" onkeypress="return noenter()" placeholder="OEN" value="<?php print $student['student_oen']; ?>">
			        		</div>			        
					    </div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding">  
			          			<label>Current School</label>
			          			<input class="pure-input-1 gl-input" name="student_school_current" type="text" onkeypress="return noenter()" placeholder="Current School" value="<?php print $student['student_school_current']; ?>">
			        		</div>
			        	</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding">  
			          			<label>Fall School</label>
			          			<input class="pure-input-1 gl-input" name="student_school_fall" type="text" onkeypress="return noenter()" placeholder="Fall School" value="<?php print $student['student_school_fall']; ?>">
			        		</div>
			        	</div>
			        	<div class="pure-u-1">
							<div class="input-padding"> 
			          			<label>Fall School Address</label>
			          			<textarea name="student_school_fall_address" class="pure-input-1 textarea_expand gl-input" style="height: 33px"><?php print $student['student_school_fall_address'];?></textarea>      
							</div>
			           </div>
			      	</div>  
				</form>
      		</section>
      		
      		<!-- ADMIN -->
	      	<section id="admin" class="page">
			    <h1>Admin</h1>
      		    <form id="admin-form" class="pure-form pure-form-stacked" method="post" action="">
			      	<div class="pure-g"> 
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
							<div class="input-padding">      	 
								<label>Prereqs (Course, Age)</label>
      							<select class="pure-input-1 gl-input" name="admin_prereq"  onKeyPress="return noenter()">
									<option value="0" <?php	if($student['admin_prereq']=="0"){print " selected";} ?>>No</option>
					            	<option value="1" <?php	if($student['admin_prereq']=="1"){print " selected";} ?>>Yes</option>
					        	</select>					        	
	    					</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
							<div class="input-padding">      	 
								<label>Waiver</label>
      							<select class="pure-input-1 gl-input" name="admin_waiver"  onKeyPress="return noenter()">
									<option value="0" <?php	if($student['admin_waiver']=="0"){print " selected";} ?>>No</option>
					            	<option value="1" <?php	if($student['admin_waiver']=="1"){print " selected";} ?>>Yes</option>
					        	</select>					        	
	    					</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
							<div class="input-padding">      	 
								<label>Paid</label>
      							<select class="pure-input-1 gl-input" name="admin_paid"  onKeyPress="return noenter()">
									<option value="0" <?php	if($student['admin_paid']=="0"){print " selected";} ?>>No</option>
					            	<option value="1" <?php	if($student['admin_paid']=="1"){print " selected";} ?>>Yes</option>
					        	</select>					        	
	    					</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
							<div class="input-padding">  
			          			<label>Amount Paid</label>
			          			<input name="admin_paid_amount" type="number" class="gl-input pure-input-1" onkeypress="return noenter()" value="<?php print $student['admin_paid_amount']; ?>">
				          	</div>
						</div>
						<div class="pure-u-1"> 
			              	<hr style="border: none;height: 1px;color: #777;background-color: #777;"/>
			            </div>
			        	<div class="pure-u-1 pure-u-sm-1-3">
							<div class="input-padding">      	 
								<label>Non-LDSB Student</label>
      							<select class="pure-input-1 gl-input" name="admin_non_ldsb"  onKeyPress="return noenter()">
									<option value="0" <?php	if($student['admin_non_ldsb']=="0"){print " selected";} ?>>No</option>
					            	<option value="1" <?php	if($student['admin_non_ldsb']=="1"){print " selected";} ?>>Yes</option>
					        	</select>					        	
	    					</div>
						</div>
						<div class="pure-u-1 pure-u-sm-1-3">
							<div class="input-padding">      	 
								<label>Out-of-Province Student</label>
      							<select class="pure-input-1 gl-input" name="admin_out_of_prov"  onKeyPress="return noenter()">
									<option value="0" <?php	if($student['admin_out_of_prov']=="0"){print " selected";} ?>>No</option>
					            	<option value="1" <?php	if($student['admin_out_of_prov']=="1"){print " selected";} ?>>Yes</option>
					        	</select>					        	
	    					</div>
						</div>
						<div class="pure-u-1 pure-u-sm-1-3">
							<div class="input-padding">      	 
								<label>Non-LDSB CCS</label>
      							<select class="pure-input-1 gl-input" name="admin_css"  onKeyPress="return noenter()">
									<option value="0" <?php	if($student['admin_css']=="0"){print " selected";} ?>>No</option>
					            	<option value="1" <?php	if($student['admin_css']=="1"){print " selected";} ?>>Yes</option>
					        	</select>					        	
	    					</div>
						</div>
						<div class="pure-u-1"> 
			              	<hr style="border: none;height: 1px;color: #777;background-color: #777;"/>
			            </div>
						<div class="pure-u-1 pure-u-sm-1-3">
							<div class="input-padding">      	 
								<label>Emailed Acceptance</label>
      							<select class="pure-input-1 gl-input" name="status_email_sent_accept"  onKeyPress="return noenter()">
									<option value="0" <?php	if($student['status_email_sent_accept']=="0"){print " selected";} ?>>No</option>
					            	<option value="1" <?php	if($student['status_email_sent_accept']=="1"){print " selected";} ?>>Yes</option>
					        	</select>					        	
	    					</div>
						</div>
						<div class="pure-u-1 pure-u-sm-1-3">
							<div class="input-padding">      	 
								<label>Read Email</label>
      							<select class="pure-input-1 gl-input" name="status_accept_read"  onKeyPress="return noenter()">
									<option value="0" <?php	if($student['status_accept_read']=="0"){print " selected";} ?>>No</option>
					            	<option value="1" <?php	if($student['status_accept_read']=="1"){print " selected";} ?>>Yes</option>
					        	</select>					        	
	    					</div>
						</div>
						<div class="pure-u-1 pure-u-sm-1-3">
							<div class="input-padding">      	 
								<label>Acceptance Confirmed</label>
      							<select class="pure-input-1 gl-input" name="status_accept_confirmed"  onKeyPress="return noenter()">
									<option value="0" <?php	if($student['status_accept_confirmed']=="0"){print " selected";} ?>>No</option>
					            	<option value="1" <?php	if($student['status_accept_confirmed']=="1"){print " selected";} ?>>Yes</option>
					        	</select>					        	
	    					</div>
						</div>
				        <div class="pure-u-1"> 
			              	<hr style="border: none;height: 1px;color: #777;background-color: #777;"/>
			            </div>
			        	<div class="pure-u-1 pure-u-sm-1-2">
							<div class="input-padding">  
			          			<label>Photo Permission</label>
					        	<select class="pure-input-1 gl-input" onkeypress="return noenter()" name="confirm_photo">
					            	<option value="Y" <?php	if($student['confirm_photo']=="Y"){print " selected";} ?>>Yes</option>
					            	<option value="N" <?php	if($student['confirm_photo']=="N"){print " selected";} ?>>No</option>
					          	</select>
			        		</div>
			        	</div>
		        		<div class="pure-u-1 pure-u-sm-1-2">
			        		<div class="input-padding">  
			          			<label>Social Media Permission</label>
					        	<select class="pure-input-1 gl-input" onkeypress="return noenter()" name="confirm_social_media" placeholder="Sex">
					            	<option value="Y" <?php	if($student['confirm_social_media']=="Y"){print " selected";} ?>>Yes</option>
					            	<option value="N" <?php	if($student['confirm_social_media']=="N"){print " selected";} ?>>No</option>
					          	</select>
			        		</div>
			        	</div>
			            <div class="pure-u-1">
			            	<div class="input-padding">
					      		<label>Admin Notes</label>
					          	<textarea name="admin_notes" class="pure-input-1 textarea_expand gl-input" style="height: 60px"><?php print $student['admin_notes']; ?></textarea>
					       	</div>
					   	</div>
					</div> 
			  	</form>
			</section>
			    			
			<!-- COURSE -->
			<section id="course" class="page">
			    <form id="course-s1" class="pure-form pure-form-stacked" method="post" action="">
			    	<h1>Course</h1>
				    <div class="pure-g"> 
						<div class="pure-u-1">
		  					<h2>Session Choices</h2>
			  			</div>
			  			<div class="pure-u-1 pure-u-sm-1-3">
							<div class="input-padding">   
								<label>Choice 1</label>
      							<select class="pure-input-1 gl-input" name="selected_session1"  onKeyPress="return noenter()">
								   	<option value="">Select...</option>
									<?php
									$sessionquery = "SELECT session_id, session_program_code, session_number FROM ss_sessions 
														WHERE session_program_code != 'ST' ORDER BY session_sortorder"; 
					            	foreach($conn->query($sessionquery) as $session)
					              	{
					                  	print "<option value=\"".$session['session_id']."\"";
										if ($session['session_id'] == $student['selected_session1']) {print " selected";}
					                  	print ">".$session['session_program_code'].$session['session_number']."</option>";
					            	}
					          		?>
					        	</select>			
	    					</div>
						</div>
						<div class="pure-u-1 pure-u-sm-1-3">
							<div class="input-padding">      	 
								<label>Choice 2</label>
      							<select class="pure-input-1 gl-input" name="selected_session2"  onKeyPress="return noenter()">
								   	<option value="">Select...</option>
									<?php
					            	foreach($conn->query($sessionquery) as $session)
					              	{
					                  	print "<option value=\"".$session['session_id']."\"";
										if ($session['session_id'] == $student['selected_session2']) {print " selected";}
					                  	print ">".$session['session_program_code'].$session['session_number']."</option>";
					            	}
					          		?>
					        	</select>					        	
	    					</div>
						</div>
						<div class="pure-u-1 pure-u-sm-1-3">
							<div class="input-padding">      	 
								<label>Accepted Session</label>
      							<select class="pure-input-1 gl-input" name="accepted_session"  onKeyPress="return noenter()">
								   	<option value="">Please select</option>
									<?php
					            	foreach($conn->query($sessionquery) as $session)
					              	{
					                  	print "<option value=\"".$session['session_id']."\"";
										if ($session['session_id'] == $student['accepted_session']) {print " selected";}
					                  	print ">".$session['session_program_code'].$session['session_number']."</option>";
					            	}
					          		?>
					        	</select>					        	
	    					</div>
						</div>
						<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding">      	 
								<label>Coop Placement</label>
      							<select class="pure-input-1 gl-input" name="admin_coop"  onKeyPress="return noenter()">
									<option value="0" <?php	if($student['admin_coop']=="0"){print " selected";} ?>>No</option>
					            	<option value="1" <?php	if($student['admin_coop']=="1"){print " selected";} ?>>Yes</option>
					        	</select>					        	
	    					</div>
						</div>
						<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding">      	 
								<label>OLP Student</label>
      							<select class="pure-input-1 gl-input" name="admin_olp"  onKeyPress="return noenter()">
								   	<option value="0" <?php	if($student['admin_olp']=="0"){print " selected";} ?>>No</option>
					            	<option value="1" <?php	if($student['admin_olp']=="1"){print " selected";} ?>>Yes</option>
					        	</select>					        	
	    					</div>
						</div>
						<div class="pure-u-1">
							<div class="input-padding">	
								<label>Placement Info</label>
						      	<textarea name="selected_placement" class="pure-input-1 textarea_expand gl-input" style="height: 40px"><?php print $student['selected_placement']; ?></textarea>
						  	</div>
						</div>
					</div>
					<hr />
					<h2>Waitlist</h2>
					<?php
					$stmt = $conn->prepare("SELECT * FROM ss_waitlist 
											LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_waitlist.waitlist_session_id
											WHERE waitlist_student_id = :waitlist_student_id 
											ORDER BY waitlist_id");
				  	$stmt->bindValue(':waitlist_student_id', $student_id);
				  	$stmt->execute();
					$waitlists = $stmt->fetchAll();
					foreach ($waitlists as $waitlist)
					{?>
						<div class="input-padding">
							<label><?php print $waitlist['session_program_code'].$waitlist['session_number']?></label>
							<input id="waitlist-notes-<?php print $waitlist['waitlist_id']; ?>" class="pure-input-1 waitlist-notes" value="<?php print $waitlist['waitlist_notes']; ?>"/>
						</div>
					<?php 
					}
					?>
				</form>
			</section>
 			    
			<!-- CONTACTS -->
			<section id="contacts" class="page">
			    <h1>Contacts</h1>
				<form id="contact-form" method="post" class="pure-form pure-form-stacked" method="post" action="">
					<div class="pure-g">
						<div class="pure-u-1">
							<div class="input-padding">		
    							<label>Student Lives With</label>
					      		<select class="pure-input-1 gl-input" name="student_custody"  onKeyPress="return noenter()">
						            <option <?php if($student['student_custody']=='Both Parents (Single Home)'){print "selected";}?>>Both Parents (Single Home)</option>
						            <option <?php if($student['student_custody']=='Both Parents (Shared Custody, Two Homes)'){print "selected";}?>>Both Parents (Shared Custody, Two Homes)</option>
						            <option <?php if($student['student_custody']=='Mother'){print "selected";}?>>Mother</option>
						            <option <?php if($student['student_custody']=='Father'){print "selected";}?>>Father</option>
						            <option <?php if($student['student_custody']=='Other (Please Specify)'){print "selected";}?>>Other (Please Specify)</option>
						          </select>
							</div>
						</div>
						<div class="pure-u-1">
							<div class="input-padding">
	        					<label>Details</label>
	        					<textarea name="student_custody_details" class="pure-input-1 textarea_expand gl-input" id="student_custody_details"><?php print $student['student_custody_details']; ?></textarea>
	    		      		</div>
						</div>
						<div class="pure-u-1">
							<hr />
							<h2>Contact 1</h2>
						</div>
    					
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">
	    			   			<label>First Name</label>
			    				<input class="pure-input-1 gl-input gl-input" name="g1_name_first" type="text" onKeyPress="return noenter()" value="<?php print $student['g1_name_first']; ?>">
	      					</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">
	      						<label>Last Name</label>
				  				<input class="pure-input-1 gl-input" name="g1_name_last" type="text" onKeyPress="return noenter()" value="<?php print $student['g1_name_last']; ?>">
							</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">      	  
									<label>Relationship to Student</label>
          							<select class="pure-input-1 gl-input" name="g1_relationship"  onKeyPress="return noenter()">
									   	<option value="">Please select</option>
										<?php
										$relationship_contact = array("Mother","Father","Stepmother","Stepfather","Legal Guardian","Foster Parent"); 
						            	foreach($relationship_contact as $relationship)
						              	{
						                  	print "<option value=\"".$relationship."\"";
											if ($student['g1_relationship'] == $relationship) {print " selected";}
						                  	print ">".$relationship."</option>";
						            	}
						          		?>
						        	</select>
        					</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">
       							<label>Email <a href="mailto:<?php print $student['g1_email']; ?>"><i class="fa fa-envelope" style="color: #222;"></i></a></label>
								<input class="pure-input-1 gl-input" name="g1_email" type="text" onKeyPress="return noenter()" value="<?php print $student['g1_email']; ?>">
							</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-lg-1-4">
							<div class="input-padding">
        						<label>Phone #1 Type</label>
        						<select class="pure-input-1 gl-input" name="g1_p1_type" onKeyPress="return noenter()" required>
				         			<option value="">Please select</option>
									<?php
			              			foreach($phone_types as $phone_type)
			                		{
			                    		print "<option value=\"".$phone_type."\"";
										if (isset($student['g1_p1_type'])) {if ($student['g1_p1_type'] == $phone_type) {print " selected";}}
			                    		print ">".$phone_type."</option>";
			                		}
			            			?>
			          			</select>
		          			</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
							<div class="input-padding">	
								<label>Phone #1<a href="tel:<?php print $student['g1_p1']; ?>"><i class="fa fa-phone" style="color: #222;"></i></a></label>
								<input class="pure-input-1 gl-input" name="g1_p1" type="text" onKeyPress="return noenter()" value="<?php print $student['g1_p1']; ?>">
	      					</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
							<div class="input-padding">
	       						<label>Phone #2 Type</label>
				  				<select class="pure-input-1 gl-input" name="g1_p2_type" onKeyPress="return noenter()" required>
				         			<option value="">Please select</option>
									<?php
			              			foreach($phone_types as $phone_type)
			                		{
			                    		print "<option value=\"".$phone_type."\"";
										if (isset($student['g1_p2_type'])) {if ($student['g1_p2_type'] == $phone_type) {print " selected";}}
			                    		print ">".$phone_type."</option>";
			                		}
			            			?>
			          			</select>
			          		</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
							<div class="input-padding">
	        					<label>Phone #2<a href="tel:<?php print $student['g1_p2']; ?>"><i class="fa fa-phone" style="color: #222;"></i></a></label>
								<input class="pure-input-1 gl-input" name="g1_p2" type="text" onKeyPress="return noenter()" value="<?php print $student['g1_p2']; ?>">
			 				</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">
		      					<label>Address</label>
								<input class="pure-input-1 gl-input" name="g1_address" type="text" onKeyPress="return noenter()" value="<?php print $student['g1_address']; ?>">
			  				</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">
			    				<label>City</label>
								<input class="pure-input-1 gl-input" name="g1_city" type="text" onKeyPress="return noenter()" value="<?php print $student['g1_city']; ?>">
			  				</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">
			  					<label>Province</label>
			    				<input class="pure-input-1 gl-input" name="g1_province" type="text" onKeyPress="return noenter()" value="<?php print $student['g1_province']; ?>">
							</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">
			  					<label>Postal Code</label>
								<input class="pure-input-1 gl-input" name="g1_postalcode" type="text" onKeyPress="return noenter()" value="<?php print $student['g1_postalcode']; ?>">
							</div>
						</div>
			        	<div class="pure-u-1">
							<div class="input-padding">
			  					<label>Contact Notes:</label>
								<textarea class="pure-input-1 gl-input" name="g1_notes"><?php print $student['g1_notes']; ?></textarea> 						
							</div>
						</div>
			 			<div class="pure-u-1">
    						<hr />
    						<h2>Contact 2</h2>
				   		</div>
						<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">
								<label>First Name</label>
			    				<input class="pure-input-1 gl-input" name="g2_name_first" type="text" onKeyPress="return noenter()" value="<?php print $student['g2_name_first']; ?>">
	     					</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">	
	     						<label>Last Name</label>
				  				<input class="pure-input-1 gl-input" name="g2_name_last" type="text" onKeyPress="return noenter()" value="<?php print $student['g2_name_last']; ?>">
							</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">
	     						<label>Relationship to Student</label>
	          					<select class="pure-input-1 gl-input" name="g2_relationship"  onKeyPress="return noenter()">
				   					<option value="">Please select</option>
									<?php
              						foreach($relationship_contact as $relationship)
                					{
                    					print "<option value=\"".$relationship."\"";
										if ($student['g2_relationship'] == $relationship) {print " selected";}
                    					print ">".$relationship."</option>";
                					}
            						?>
          						</select>
        					</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">
								<label>Email <a href="mailto:<?php print $student['g2_email']; ?>"><i class="fa fa-envelope" style="color: #222;"></i></a></label>
								<input class="pure-input-1 gl-input" name="g2_email" type="text" onKeyPress="return noenter()" value="<?php print $student['g2_email']; ?>">
		  					</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-lg-1-4">
							<div class="input-padding">
        						<label>Phone #1 Type</label>
        						<select class="pure-input-1 gl-input" name="g2_p1_type" onKeyPress="return noenter()" required>
				         			<option value="">Please select</option>
									<?php
			              			foreach($phone_types as $phone_type)
			                		{
			                    		print "<option value=\"".$phone_type."\"";
										if (isset($student['g2_p1_type'])) {if ($student['g2_p1_type'] == $phone_type) {print " selected";}}
			                    		print ">".$phone_type."</option>";
			                		}
			            			?>
			          			</select>
		          			</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
							<div class="input-padding">	
								<label>Phone #1<a href="tel:<?php print $student['g2_p1']; ?>"><i class="fa fa-phone" style="color: #222;"></i></a></label>
								<input class="pure-input-1 gl-input" name="g2_p1" type="text" onKeyPress="return noenter()" value="<?php print $student['g2_p1']; ?>">
	      					</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
							<div class="input-padding">
	       						<label>Phone #2 Type</label>
				  				<select class="pure-input-1 gl-input" name="g2_p2_type" onKeyPress="return noenter()" required>
				         			<option value="">Please select</option>
									<?php
			              			foreach($phone_types as $phone_type)
			                		{
			                    		print "<option value=\"".$phone_type."\"";
										if (isset($student['g2_p2_type'])) {if ($student['g2_p2_type'] == $phone_type) {print " selected";}}
			                    		print ">".$phone_type."</option>";
			                		}
			            			?>
			          			</select>
			          		</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
							<div class="input-padding">
	        					<label>Phone #2<a href="tel:<?php print $student['g2_p2']; ?>"><i class="fa fa-phone" style="color: #222;"></i></a></label>
								<input class="pure-input-1 gl-input" name="g2_p2" type="text" onKeyPress="return noenter()" value="<?php print $student['g2_p2']; ?>">
			 				</div>
						</div>
						<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">
		      					<label>Address</label>
								<input class="pure-input-1 gl-input" name="g2_address" type="text" onKeyPress="return noenter()" value="<?php print $student['g2_address']; ?>">
			  				</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">
			    				<label>City</label>
								<input class="pure-input-1 gl-input" name="g2_city" type="text" onKeyPress="return noenter()" value="<?php print $student['g2_city']; ?>">
			  				</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">
			  					<label>Province</label>
			    				<input class="pure-input-1 gl-input" name="g2_province" type="text" onKeyPress="return noenter()" value="<?php print $student['g2_province']; ?>">
							</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3 pure-u-lg-1-4">
							<div class="input-padding">
			  					<label>Postal Code</label>
								<input class="pure-input-1 gl-input" name="g2_postalcode" type="text" onKeyPress="return noenter()" value="<?php print $student['g2_postalcode']; ?>">
							</div>
						</div>
			        	<div class="pure-u-1">
							<div class="input-padding">
			  					<label>Contact Notes:</label>
								<textarea class="pure-input-1 textarea_expand gl-input" name="g2_notes"><?php print $student['g2_notes']; ?></textarea>
							</div>
						</div>
			        	<div class="pure-u-1">
			 				<hr />
			 			</div>
						<div class="pure-u-1">
							<h2>Emergency Contact</h2>
    					</div>
    					<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding">
        						<label>Relationship to Student</label>
          						<select class="pure-input-1 gl-input"  class="pure-input-1" name="c_relationship"  onKeyPress="return noenter()">
						          	<option value="">Please select</option>
									<?php               
									$relationship_emerg = array("Stepmother","Stepfather","Grandmother","Grandfather","Sibling","Aunt","Uncle","Family Friend"); 
						          foreach($relationship_emerg as $relationship)
						            {
						             	print "<option value=\"".$relationship."\"";
										if (isset($student['c_relationship'])) {if ($student['c_relationship'] == $relationship) {print " selected";}}
						                print ">".$relationship."</option>";
						         	}
						          	?>
						       	</select>
      						</div>
  						</div>
    					<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding">
       							<label>First Name</label>
			    				<input class="pure-input-1 gl-input" name="c_name_first" type="text" onKeyPress="return noenter()" value="<?php print $student['c_name_first']; ?>">				  	
							</div>
  						</div>
    					<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3">
							<div class="input-padding"> 	
			   					<label>Last Name</label>
			    				<input class="pure-input-1 gl-input" name="c_name_last" type="text" onKeyPress="return noenter()" value="<?php print $student['c_name_last']; ?>">
							</div>
  						</div>
    					<div class="pure-u-1 pure-u-sm-1-2 pure-u-lg-1-4">
							<div class="input-padding">
								<label>Phone #1 Type</label>
        						<select class="pure-input-1 gl-input" name="c_p1_type" onKeyPress="return noenter()" required>
				         			<option value="">Please select</option>
									<?php
			              			foreach($phone_types as $phone_type)
			                		{
			                    		print "<option value=\"".$phone_type."\"";
										if (isset($student['c_p1_type'])) {if ($student['c_p1_type'] == $phone_type) {print " selected";}}
			                    		print ">".$phone_type."</option>";
			                		}
			            			?>
			          			</select>
		          			</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
							<div class="input-padding">	
								<label>Phone #1<a href="tel:<?php print $student['c_p1']; ?>"><i class="fa fa-phone" style="color: #222;"></i></a></label>
								<input class="pure-input-1 gl-input" name="c_p1" type="text" onKeyPress="return noenter()" value="<?php print $student['c_p1']; ?>">
	      					</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
							<div class="input-padding">
	       						<label>Phone #2 Type</label>
				  				<select class="pure-input-1 gl-input" name="c_p2_type" onKeyPress="return noenter()" required>
				         			<option value="">Please select</option>
									<?php
			              			foreach($phone_types as $phone_type)
			                		{
			                    		print "<option value=\"".$phone_type."\"";
										if (isset($student['c_p2_type'])) {if ($student['c_p2_type'] == $phone_type) {print " selected";}}
			                    		print ">".$phone_type."</option>";
			                		}
			            			?>
			          			</select>
			          		</div>
						</div>
			        	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-4">
							<div class="input-padding">
	        					<label>Phone #2<a href="tel:<?php print $student['c_p2']; ?>"><i class="fa fa-phone" style="color: #222;"></i></a></label>
								<input class="pure-input-1 gl-input" name="c_p2" type="text" onKeyPress="return noenter()" value="<?php print $student['c_p2']; ?>">
			 				</div>
						</div>
					</div>
				</form>
			</section>

			<!-- MEDICAL -->
			<section id="medical" class="page">
			    <h1>Medical Information</h1>
			    <form id="medical-form" style="text-align: center;" method=post class="pure-form pure-form-stacked" method="post" action="">
					<button id="med-general-toggle" class="med-toggle-button plaintext-button" style="padding-top: 5px;">general</button>
					<div class="pure-g med-toggle" id="med-general">      	
						<div class="pure-u-1 pure-u-sm-1-2 pure-u-lg-1-4">
							<div class="input-padding">
								<label>Health Card Number</label>																																												
						      	<input class="pure-input-1 gl-input" type="text" name="student_health_card" onKeyPress="return noenter()" value="<?php print $student['student_health_card'];?>" />
				   	 		</div>
				   	 	</div>
				   	 	<div class="pure-u-1 pure-u-sm-1-2 pure-u-lg-1-4">
							<div class="input-padding">
				   	 			<label>Date of Last Tetanus Shot</label>
				          		<input class="pure-input-1 gl-input" type="text" id="student_health_tetanus" name="student_health_tetanus" onkeypress="return noenter()" value="<?php print $student['student_health_tetanus'];?>" />
  							</div>
				   	 	</div>
				   	 	<div class="pure-u-1 pure-u-sm-1-2 pure-u-lg-1-4">
							<div class="input-padding">
							   		<label>Family Doctor</label>
							   		<input  class="pure-input-1 gl-input" name="student_health_doctor" type="text" onKeyPress="return noenter()" value="<?php print $student['student_health_doctor']; ?>">
							</div>
				   	 	</div>
				   	 	<div class="pure-u-1 pure-u-sm-1-2 pure-u-lg-1-4">
							<div class="input-padding">
   								<label>Doctor's Office Phone Number</label>
							    <input class="pure-input-1 gl-input" name="student_health_doctorphone" type="text" onKeyPress="return noenter()" value="<?php print $student['student_health_doctorphone']; ?>">
  							</div>
				   	 	</div>
				   	</div>
					<hr />
					
					<button id="med-flags-toggle" class="med-toggle-button plaintext-button" style="padding-top: 5px;">flags</button>
					<input style="min-height: 0px;vertical-align: -3px;" name="admin_flag_checked" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($student['admin_flag_checked'] == 1){print " checked";} ?>>
					<div class="pure-g med-toggle medchecklist" id="med-flags">      	
						<div class="pure-u-1 pure-u-sm-1-4">
				            <div class="checkstyle">
					        	<input name="admin_mf" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($student['admin_mf'] == 1){print " checked";} ?>>
					          	<label style="color: #666;"><i class="fa fa-flag"></i> Med</label>
					        </div>
				        	<div class="checkstyle">
					        	<input name="admin_mf_contact" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($student['admin_mf_contact'] == 1){print " checked";} ?>>
					          	<label><i class="fa fa-flag"></i> Med Contact</label>
					        </div>
		              		<div class="checkstyle">
					        	<input name="admin_mfc" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($student['admin_mfc'] == 1){print " checked";} ?>>
					          <label><i class="fa fa-flag"></i> Med Closed</label>
					        </div>
					        <hr class="med-form-break" />
					  </div>
					  <div class="pure-u-1 pure-u-sm-1-4">
							<div class="checkstyle"> 
					          	<input name="admin_df" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($student['admin_df'] == 1){print " checked";} ?>>
					          	<label style="color: #666;"><i class="fa fa-flag"></i> Diet</label>
					        </div>
				        	<div class="checkstyle"> 
					          	<input name="admin_df_contact" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($student['admin_df_contact'] == 1){print " checked";} ?>>
				          		<label><i class="fa fa-flag"></i> Diet Contact</label>
					        </div>
			            	<div class="checkstyle"> 
					          	<input name="admin_dfc" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($student['admin_dfc'] == 1){print " checked";} ?>>
					          	<label><i class="fa fa-flag"></i> Diet Closed</label>
					        </div>
					        <hr class="med-form-break" />
						</div>
					  	<div class="pure-u-1 pure-u-sm-1-4">
					  	    <div class="checkstyle">
					        	<input name="admin_if" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($student['admin_if'] == 1){print " checked";} ?>>
					          	<label style="color: #666;"><i class="fa fa-flag"></i> IEP</label>
					        </div>
							<div class="checkstyle">
					        	<input name="admin_if_contact" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($student['admin_if_contact'] == 1){print " checked";} ?>>
					          	<label><i class="fa fa-flag"></i> IEP Contact</label>
					        </div>
			            	<div class="checkstyle">
					        	<input name="admin_ifc" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($student['admin_ifc'] == 1){print " checked";} ?>>
					          	<label><i class="fa fa-flag"></i> IEP Closed</label>
					        </div>
				   	 	</div>
				   	 	<div class="pure-u-1 pure-u-sm-1-4">
					  	    <div class="checkstyle">
					        	<input name="admin_sf" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($student['admin_sf'] == 1){print " checked";} ?>>
					          	<label style="color: #666;"><i class="fa fa-flag"></i> SM</label>
					        </div>
							<div class="checkstyle">
					        	<input name="admin_sf_contact" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($student['admin_sf_contact'] == 1){print " checked";} ?>>
					          	<label><i class="fa fa-flag"></i> SM Contact</label>
					        </div>
			            	<div class="checkstyle">
					        	<input name="admin_sfc" type="checkbox" class="gl-check" onkeypress="return noenter()" value="1"<?php if ($student['admin_sfc'] == 1){print " checked";} ?>>
					          	<label><i class="fa fa-flag"></i> SM Closed</label>
					        </div>
				   	 	</div>
				   	</div>	
		   	 		<hr />
		   	 		
					<button id="med-details-toggle" class="med-toggle-button plaintext-button" style="padding-top: 5px;">details</button>
					<div class="med-toggle" id="med-details">
						<div class="input-padding">
							<label>Has this student ever been hospitalized?</label>
							<textarea class="pure-input-1 textarea_expand gl-input" name="student_health_hospitalized_details"><?php print $student['student_health_hospitalized_details']; ?></textarea>
						</div>		
						<div class="input-padding">
							<label>Has this student suffered any physical activity related injuries in the past year?</label>
					    	<textarea class="pure-input-1 textarea_expand gl-input" name="student_health_injuries_details"><?php print $student['student_health_injuries_details']; ?></textarea>
					    </div>
					
						<div class="input-padding">	
		    				<label>Does this student take regular medication?</label>								
							<textarea class="pure-input-1 textarea_expand gl-input" name="student_health_meds_details"><?php print $student['student_health_meds_details']; ?></textarea>
					    </div>
						<div class="input-padding">
					       	<label>Does this student have allergies (seasonal, peanuts, bees, etc.)</label>
							<textarea class="pure-input-1 textarea_expand gl-input" name="student_health_allergies_details"><?php if (isset($student['student_health_allergies_details'])) {print $student['student_health_allergies_details'];} ?></textarea>
					    </div>
						<div class="input-padding">
					       	<label>Does this student carry an epipen?</label>
							<textarea class="pure-input-1 textarea_expand gl-input" name="student_health_epipen_details"><?php if (isset($student['student_health_epipen_details'])) {print $student['student_health_epipen_details'];} ?></textarea>
					    </div>
						<div class="input-padding">
					       	<label>Does this student have asthma?</label>
							<textarea class="pure-input-1 textarea_expand gl-input" name="student_health_asthma_details"><?php if (isset($student['student_health_asthma_details'])) {print $student['student_health_asthma_details'];} ?></textarea>
					    </div>
						<div class="input-padding">
							<label>Has this student ever suffered from epilepsy?</label>
							<textarea class="pure-input-1 textarea_expand gl-input" name="student_health_epilepsy_details"><?php if (isset($student['student_health_epilepsy_details'])) {print $student['student_health_epilepsy_details'];} ?></textarea>	
					    </div>
						<div class="input-padding">
					       	<label>Does this student have diabetes?</label>
							<textarea class="pure-input-1 textarea_expand gl-input" name="student_health_diabetes_details"><?php print $student['student_health_diabetes_details']; ?></textarea>
					    </div>
						<div class="input-padding">
		    			   	<label>Has this student seen a counselor or therapist for psychological or emotional reasons within the last two years?</label>
							<textarea class="pure-input-1 textarea_expand gl-input" name="student_health_counselor_details"><?php print $student['student_health_counselor_details']; ?></textarea>
					    </div>
						<div class="input-padding">
					       	<label>Does this student suffer from anxiety or homesickness?</label>
							<textarea class="pure-input-1 textarea_expand gl-input" name="student_health_anxiety_details"><?php print $student['student_health_anxiety_details']; ?></textarea>
					    </div>
						<div class="input-padding">
							<label>Are there any other limitations on this student's activities?</label>
							<textarea class="pure-input-1 textarea_expand gl-input" name="student_health_limitations_details"><?php print $student['student_health_limitations_details']; ?></textarea>
					    </div>
						<div class="input-padding">
					    	<label>Are there any other conditions that might affect this student's health or the well being of others?</label>
							<textarea class="pure-input-1 textarea_expand gl-input" name="student_health_others_details"><?php print $student['student_health_others_details']; ?></textarea>
					    </div>
						<div class="input-padding">
					  		<label>Does this student have any dietary restrictions? (Vegetarian, allergies, picky eater...)</label>
					   		<textarea class="pure-input-1 textarea_expand gl-input" name="student_health_dietary_details"><?php print $student['student_health_dietary_details']; ?></textarea>
					    </div>
					</div>
					<hr />
					
					<button id="med-comm-toggle" class="med-toggle-button plaintext-button" style="padding-top: 5px;">communications</button>
					<div id="med-comm" class="med-toggle" >
		            	<div class="input-padding">
							<label>Med/Dietary/IEP Notes</label>
				          	<textarea name="admin_flag_notes" class="pure-input-1 textarea_expand gl-input" style="height: 60px"><?php print $student['admin_flag_notes']; ?></textarea>
				     	</div>
		      		</div>
				</form>
			</section>
			
			<!-- ADDITIONAL -->
			<section id="additional" class="page">
			 	<h1>Additional Information</h1>
			   <form id="additional-form" method=post class="pure-form pure-form-stacked" method="post" action="">
					<div class="pure-g">
	      				<div class="pure-u-1 pure-u-md-1-2">
							<div class="input-padding">
						  		<label>Learning Accommodations</label>
								<textarea class="pure-input-1 textarea_expand gl-input" name="student_learning_accommodations"><?php print $student['student_learning_accommodations'];?></textarea>
							</div>
						</div>
						<div class="pure-u-1 pure-u-md-1-2">
							<div class="input-padding">
								<label>Other Experience</label>
								<textarea class="pure-input-1 textarea_expand gl-input" name="student_experience"><?php print $student['student_experience']; ?></textarea>
							</div>
						</div>
						<div class="pure-u-1 pure-u-md-1-2">
							<div class="input-padding">
								<label>Siblings</label>
			    				<input class="pure-input-1 gl-input" name="student_siblings" type="text" onKeyPress="return noenter()" value="<?php print $student['student_siblings']; ?>">
							</div>
						</div>
						<div class="pure-u-1 pure-u-md-1-2">
							<div class="input-padding">		
    							<label>Bus Location</label>
					      		<select class="pure-input-1 gl-input" name="student_bus_location"  onKeyPress="return noenter()">
						            <option value="">Select Location</option>
						            <option <?php if($student['student_bus_location']=='LCVI'){print "selected";}?>>LCVI</option>
						            <option <?php if($student['student_bus_location']=='Elginburg PS'){print "selected";}?>>Elginburg PS</option>
						            <option <?php if($student['student_bus_location']=='Loughborough PS'){print "selected";}?>>Loughborough PS</option>
						            <option <?php if($student['student_bus_location']=='Gould Lake'){print "selected";}?>>Gould Lake</option>
						         </select>
							</div>
						</div>
						<!-- Experience -->
						<div class="pure-u-1">
							<hr />
							<h2>Past Gould Lake Programs</h2>
						</div>
						<div class="pure-u-1">
							<div class="pure-g">
								<?php 									
								$j=0;
								$programarray = array("oe"=>"OE","gap"=>"GAP","q"=>"Quest","olp"=>"OLP", "outreach"=>"OR","op"=>"OP","os"=>"OS","solo"=>"SOLO","wic"=>"WIC",
													  		"lt"=>"Long Trail","kic"=>"KIC");
								foreach($programarray as $key => $programname)
								{?>
									<div class="pure-u-1-2 pure-u-sm-1-5">
										<div class="input-padding">
											<label><?php print $programname; ?></label>
											<select class="pure-input-1 gl-input" name="completed_<?php print $key; ?>_year">
												<option value="">Year</option>
												<?php
						      					$startyear = date("Y");
								  				for($i=-8; $i<=0; $i++)
												{
													$printyear = $startyear + $i;
													$program_year = "completed_".$key."_year";
													print $i."<option value=\"".$printyear."\"";
													
													if (isset($student[$program_year]))
													{if ($student[$program_year] == $printyear){print " selected";}}
													print ">".$printyear."</option>";
												}?>
											</select>
										</div>
									</div>
								<?php
								}
								?>
							</div>								
						</div>
												
						<!-- Swimming -->
						<div class="pure-u-1">						
							<hr />
							<h2>Swimming Ability</h2>
						</div>
						<div class="pure-u-1">
							<div class="input-padding">
	  							<label>This student can swim 40m and can tread water for 5 minutes without assistance</label>
	  							<select class="pure-input-1 gl-input" name="student_swimming">
							        <option value="">Select...</option>
							        <option value="Yes" <?php if ($student['student_swimming'] == "Yes") {print " selected";} ?>>Yes</option>
							        <option value="No" <?php if ($student['student_swimming'] == "No") {print " selected";} ?>>No</option>
			      				</select>
			      			</div>
					    </div>
					    <div class="pure-u-1">
   							<div class="input-padding">
								<label>Swimming Ability</label>
								<textarea class="pure-input-1 textarea_expand gl-input" name="student_swimming_details"><?php print $student['student_swimming_details']; ?></textarea>
							</div>
						</div>
      				</div>
    			</form>
    		</section> 
    		
    		<!-- ASSESSMENT -->
			<section id="assessment" class="page">
			 	<h1>Assessment Information</h1>
			    <form id="assess-form" class="pure-form pure-form-stacked" method="post" action="">
					<div class="pure-g">
			            <div class="pure-u-1 pure-u-sm-1-3">
   							<div class="input-padding">
			            		<label>Credit Code</label> 
			                   	<input type="text" class="pure-input-1 gl-input" name="admin_credit" value="<?php print $student['admin_credit']; ?>">
			            	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-3">
   							<div class="input-padding">
   								<label>Absence Notes</label>
			            		<input type="text" class="pure-input-1 gl-input" name="admin_absent" value="<?php print $student['admin_absent']; ?>">
			            	</div>
			            </div>
			            <div class="pure-u-1 pure-u-sm-1-3">
   							<div class="input-padding">
   								<label>Confirmed by Rob</label>
			            		<select class="pure-input-1 gl-input" name="admin_assess_complete">
							        <option value="">Select...</option>
							        <option value="1" <?php if ($student['admin_assess_complete'] == "1") {print " selected";} ?>>Yes</option>
							        <option value="0" <?php if ($student['admin_assess_complete'] == "0") {print " selected";} ?>>No</option>
			      				</select>
			            	</div>
			            </div>
			       	</div>
				</form>
	            <div><hr /></div>
	            <div id="assessment-table"></div>
    		</section> 
    		
    		<!-- Upload Docs -->
    		<section id="uploads" class="page">
    			<h1>Attached Files</h1>
    			<div class="pure-g">
    				<div class="pure-u-1 pure-u-sm-1-3">
    					<div style="padding: 10px">
	    					<h2>View Uploaded Files</h2>
	    					<?php
	    					$stmt = $conn->prepare("SELECT * FROM ss_reg_files WHERE file_student_id = :file_student_id ORDER BY file_title");
	    					$stmt->bindValue(':file_student_id', $_SESSION['student_id']);
						   $stmt->execute();
						   $filelist = $stmt->fetchAll();
							foreach ($filelist as $file)
					    	{
					    		?>
					    		<a id="student-file-<?php print $file['file_id']; ?>" style="display: inline;" class="file-list" href="docs/<?php print $file['file_name']; ?>" target="_blank">
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
    				<div class="pure-u-1 pure-u-sm-2-3">
    					<div style="padding: 10px">
						 	<h2>Upload New File</h2>
						 	<form class="pure-form pure-form-stacked" action="process-upload-doc.php" method="POST" enctype="multipart/form-data">
								<div class="pure-g">
									<div class="pure-u-1 pure-u-lg-1-2">
										<label>Document Title</label>
										<input class="pure-input-1" type="text" name="file_title" />
									</div>
									<div class="pure-u-1 pure-u-lg-1-2">
										<label>File to Upload</label>
										<div class="pure-input-1 file-input">
											<input  class="pure-input-1" type="file" name="student_file" />
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
<style>.med-toggle{display:none;}</style>
<script>
	$(document).ready(function() {
		
		$('#med-general-toggle').click(function(e) {
			$('#med-general').toggle();
			return false;
		});
		$('#med-flags-toggle').click(function(e) {
			$('#med-flags').toggle();
			return false;
		});
		$('#med-details-toggle').click(function(e) {
			$('#med-details').toggle();
			return false;
		});
		$('#med-comm-toggle').click(function(e) {
			$('#med-comm').toggle();
			return false;
		});

		var hash = window.location.hash.substr(1);
		if (hash == "") {
			$('#profile').fadeIn('fast');
			$('#profile').addClass("active");
		}
		else {
			hash = "#" + hash;
			$.when($(".page").removeClass('active')).done(function () {
				$.when($('.page').fadeOut('fast')).done(function() {
					//alert(hash);
					$(hash).addClass("active");
					$(hash).fadeIn('fast');
					$('#main').scrollTop();
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
				$.when($(".page").removeClass('active')).done(function () {
					$.when($('.page').fadeOut('fast')).done(function() {
						//alert(hash);
						$(hash).addClass("active");
						$(hash).fadeIn('fast');
						$('#main').scrollTop();
						if (hash == '#assessment')
						{
							$('#working').fadeIn('fast');
							$("#assessment-table").load("student-assess-table.php",{student_id : <?php print $student_id; ?>},function() {
								$("#assessment-table").fadeIn("fast");
								$('#working').fadeOut('fast');
							});
						}
					});
				});
			}
		}
		
		//SHOW STATUS ON ENTRY
		$("#student_status").load("student-status.php",'',function() {$("#student_status").fadeIn("fast");});
		
		//LOAD ASSESSMENT ON ENTRY
		$("#assessment-table").load("student-assess-table.php",{student_id : <?php print $student_id; ?>},function() {$("#assessment-table").fadeIn("fast");});

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
				if ($('.right-menu').hasClass("active")) 
				{
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
			if (id == "#email-confirm") {
				if (1 == <?php if ($student['accepted_session'] != 0) {print "1";} else {print "0";} ?>) {
					if (1 == <?php if ($student['status_email_sent_accept'] == 0) {print "1";} else {print "0";} ?>) {
						if (1 == <?php if ($student['admin_paid'] != 0 OR $student['admin_prereq'] != 0 OR $student['admin_waiver'] != 0) {print "1";} else {print "0";} ?>) {
							if (confirm("Are you sure you want to send an email confirmation to <?php print $student['student_name_common']; ?>?") == true) {
								$.ajax({
									type : "POST",
									url : "process-email-send-confirm.php",
									success : function() {alert("Confirmation email has been sent.");return false;}
								});
							}	
							else {return false;}
						}
						else {
							if (confirm("<?php print $student['student_name_common']; ?> has an issue with their registration.  Are you sure you want to send an email confirmation?") == true) {
								$.ajax({
									type : "POST",
									url : "process-email-send-confirm.php",
									success : function() {alert("Confirmation email has been sent.");return false;}
								});
							}	
							else {return false;}
						}
					}
					else {alert("A confirmation email has already been sent to <?php print $student['student_name_common']; ?>."); return false;}	
			    } 
				else {alert("<?php print $student['student_name_common']; ?> has not been accepted into a session."); return false;}		   			
			}
			else if (id == "#email-forms") {
				if (confirm("Are you sure you want to send a forms package to <?php print $student['student_name_common']; ?>?") == true) {
			        $.ajax({
						type : "POST",
						url : "process-email-send-forms.php",
						success : function() {alert("Forms email has been sent.");return false;}
					});
			    } 
			    else {return false;}				
			}
			else if (id == "#duplicate") {
				if (confirm("Are you sure you want to duplicate <?php print $student['student_name_common']; ?>'s registration?") == true) {
			        window.location = "process-student-duplicate.php";
			    } 
			    else {return false;}				
			}
			else if (id == "#delete") {
				if (confirm("Are you sure you want to delete <?php print $student['student_name_common']; ?>'s registration?") == true) {
					if (confirm("Are you 100% sure?") == true) {
				        $.ajax({
							type : "POST",
							url : "process-student-delete.php",
							success : function() {window.location = "../student-admin/index.php";}
						});
					}
					else {return false;}	
			    } 
			    else {return false;}				
			}
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
					val : val
				},
				success : function() {
					$('.gl-input').css('background', 'transparent');
					$("#student_status").load("student-status.php",'',function() {$("#student_status").fadeIn("fast");});
					if (name == "student_name_last") {$('#last-name').replaceWith(val + ",");}
					if (name == "student_name_common") {$('#first-name').replaceWith(val);}
				}
			});
			return false;
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
					val : val
				},
				success : function() {
					$('.gl-input').css('background', 'transparent');
					$("#student_status").load("student-status.php",'',function() {$("#student_status").fadeIn("fast");});
				}
			});
			return false;
		});
		
		$('.del-file').click(function(e) {
			id = $(this).attr("id");
			val = id.replace("del-file-", "");
			id = "#student-file-" + val;
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
		
		$(".waitlist-notes").change(function(){
			var id = this.id;
			id = id.replace("waitlist-notes-", "");
			//alert(id);
			val = $(this).val();
			//alert(val); return false;
			$.ajax({
				type : "POST",
				url : "process-waitlist-notes.php",
				data : {
					id : id, val : val
				},
				success : function() {
				}
			});
		});
		
		$(".textarea_expand").on("keyup", function() {
			this.style.height = "1px";
			this.style.height = (this.scrollHeight) + "px";
		});
		
		$('#back-button').click(function() {
			window.location = "../student-admin/index.php";
		});
		
		$('#logout-button').click(function() {
			window.location = "../shared/logout.php";
		});
		
	});
	
</script>
