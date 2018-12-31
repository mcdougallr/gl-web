<?php 
	$page = 2;
	include ('../regolp/header.php');	
	
	if ($_SESSION['registration_id'] != 0) {
		$stmt = $conn->prepare("select * from ss_registrations where registration_id = :reg_id");
		$stmt -> bindValue(':reg_id', $_SESSION['registration_id']);
		$stmt-> execute();
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
	}


if ($_SESSION['registration_id'] == 0){header("Location: http://outed.limestone.on.ca/regfiles/");}	 
else 
{
?>
	<div id="gl-main">
		<?php include("../regolp/menu.php"); ?>
		<div id="gl-wrapper">		  	
			<form novalidate class="pure-form pure-form-stacked" method="post" parsley-validate>
		      <div class="pure-g">
		      	<div class="pure-u-1">
			     		<p>Please fill out the student profile for us...</p>
			      </div>
		      	<div class="pure-u-1 pure-u-sm-1-3">
		      		<div class="form-padding">
						 <label>Common First Name</label>
					      <input class="pure-input-1" name="student_name_common" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['student_name_common'])) {print $data['student_name_common'];} ?>" required />
			      	</div>
		      	</div>
		      	<div class="pure-u-1 pure-u-sm-1-3">
		      		<div class="form-padding">
				      	<label>Legal First Name</label>
					      <input class="pure-input-1" name="student_name_first" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['student_name_first'])) {print $data['student_name_first'];} ?>" required />
				      </div>
			      </div>
			      <div class="pure-u-1 pure-u-sm-1-3">
		      		<div class="form-padding">
				      	<label>Last Name</label>
					      <input class="pure-input-1" name="student_name_last" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['student_name_last'])) {print $data['student_name_last'];} ?>" required />
			      	</div>
			      </div>
			      
		      	<div class="pure-u-1 pure-u-sm-1-3">
			      	<div class="form-padding">
			      		<label>Date of Birth</label>
				      	<input class="pure-input-1" type="date" id="student_dob" name="student_dob" value="<?php if (isset($data['student_dob'])) {print $data['student_dob'];} ?>" required/>
					  	</div>
					  </div>
					  <div class="pure-u-1 pure-u-sm-1-3">
							<div class="form-padding">
								<label>Sex</label>
					      <select class="pure-input-1" name="student_sex" onkeypress="return noenter()" required>
					        <option value="">Select...</option>
					        <option value="Female" <?php if (isset($data['student_sex'])) {if ($data['student_sex'] == "Female") {print " selected";}} ?>>Female</option>
					        <option value="Male" <?php if (isset($data['student_sex'])) {if ($data['student_sex'] == "Male") {print " selected";}} ?>>Male</option>
					      </select>		
					    </div>    
			    	</div>
			    	<div class="pure-u-1 pure-u-sm-1-3">
					  	<div class="form-padding">
					  		<label>Shirt Size</label>
								<select class="pure-input-1" name="student_shirtsize" onkeypress="return noenter()" required>
					        <option value="">Select...</option>
					        <?php
					        $shirtsizes = array("S" => "Adult Small", "M" => "Adult Medium", "L" => "Adult Large", "XL" => "Adult Extra Large");
					
					        foreach ($shirtsizes as $code => $text) {
					          print "<option value=" . $code;
					          if (isset($data['student_shirtsize'])) {if ($data['student_shirtsize'] == $code) {print " selected";}}
					          print ">" . $text . "</option>";
					        }
					        ?>
					      </select>
					    </div>
				    </div>
					  
			    	
					  <div class="pure-u-1 pure-u-sm-1-3">
				    	<div class="form-padding">
				    		<label>Student Email</label>
						    <input class="pure-input-1" name="student_email" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['student_email'])) {print $data['student_email'];} ?>">
							</div>
						</div>
					  <div class="pure-u-1 pure-u-sm-1-3">
					  	<div class="form-padding">
			    			<label>Current Grade</label>
					      <select class="pure-input-1" name="student_grade" onkeypress="return noenter()" required>
					        <option value="">Select...</option><?php
					        for ($i = 6; $i < 13; $i++) {
					          print "<option value=" . $i;
					          if (isset($data['student_grade'])) {if ($data['student_grade'] == $i) {print " selected";}}
					          print ">" . $i . "</option>";
					        }
					        ?>
					      </select>
					    </div>
					  </div>
					  <div class="pure-u-1 pure-u-sm-1-3">
				    	<div class="form-padding">
				    		<label>OEN <i id="OENtipbutton" title="Ontario Education Number" style="color: 060;" class="fa fa-question"></i>
				    			<span id="OENtip">
				    				The Ontario Education Number is a 9-digit number that can be found on a student's report card.  
				    				If you are from outside Ontario or from an independent school, please just leave this spot blank.
				    			</span></label>
						    <input class="pure-input-1" name="student_oen" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['student_oen'])) {print $data['student_oen'];} ?>">
							</div>
						</div>
					  <div class="pure-u-1 pure-u-sm-1-2">
							<div class="form-padding">
								<label>Current School</label>
					      <input class="pure-input-1" name="student_school_current" type="text" id="student_name_first" onKeyPress="return noenter()" value="<?php if (isset($data['student_school_current'])) {print $data['student_school_current'];} ?>" />
					    </div>
					  </div>
					  <div class="pure-u-1 pure-u-sm-1-2">
							<div class="form-padding">
								<label>School for Fall 2018</label>
					      <input class="pure-input-1" name="student_school_fall" type="text" id="student_name_first" onKeyPress="return noenter()" value="<?php if (isset($data['student_school_fall'])) {print $data['student_school_fall'];} ?>" />
					    </div>
					  </div>
				    <div class="pure-u-1"> 
				    	<div class="form-padding">
				      	<label>Fall 2018 School Address</label>
								<label style="font-size: .75em;font-variant: normal;"><em>Only required if the school is outside the Limestone District School Board.<br />Please include the school board.</em></label>
					      <textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_school_fall_address"><?php if (isset($data['student_school_fall_address'])) {print $data['student_school_fall_address'];} ?></textarea>      
							</div>
						</div>
				    <div class="pure-u-1">  	
							<hr />
							<p>Gould Lake summer courses are secondary school credit programs and therefore involve reading, 
								writing and independent work by the student.  In the box below, describe additional measures taken 
								at school to accommodate any learning challenges so that we can help the student be as successful 
								as possible.</p>
							<p>If relevant, please enclose a copy of the student's current Individual 
								Education Plan (IEP), issued by the student's current school, with the registration package that 
								you send to the Gould Lake office.</p>
							<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_learning_accommodations"><?php 
							if (isset($data['student_learning_accommodations'])) 
							{
								if ($data['student_learning_accommodations']!="")
									{print $data['student_learning_accommodations'];}
							}
							?></textarea>
						</div>
						<div class="pure-u-1 button-home">
			     		<button class="gl-submit-button">Save and continue to contact <i class="fa fa-hand-o-right"></i></button>
			      	</div>
		      	</div>
		    </form>
		</div>
	</div>
	
	<?php include ('../regolp/footer.php'); 
}
?>

<script>



	(function() {  
		$('#student_dob').datepicker({
	 	minDate: "1995-01-01",
	 	maxDate: "2006-12-31",
	 	dateFormat: 'yy-mm-dd',
	   	changeMonth: true,
			changeYear: true
			}); 
	})();
   
	$(document).ready(function(){
		$(document).click(function(e) { 
    if(!$(e.target).closest('#OENtipbutton').length) {
        if($('#OENtip').is(":visible")) {
            $('#OENtip').hide()
        }
    }        
})
	 	$(".gl-submit-button").click(function(){
			$(".pure-form").submit(function(e){
		  	e.preventDefault();
				if ( $(this).parsley().isValid() ) {
					$("#working").show();
					var senddata = $(this).serialize();
		   	 	//alert (senddata);return false;
		   	 	
		   	 	$.ajax({
		        type: "POST",
		        url: "process-2-profile.php",
		        data: senddata,
		        success: function() {
							window.location.href = "page3.php";
	          }
	     	 	});	     	 	
	     	}
	 		});
	 	});	 
	 	
	 	$(".textarea_expand").on("keyup", function(){
      this.style.height = "1px";
      this.style.height = (this.scrollHeight) + "px"; 
    });	
    
    $("#OENtipbutton").click(function(){
      $("#OENtip").show();
    });	
    
	});

</script>