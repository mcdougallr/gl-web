<?php 
	$page = 2;
	include ('header.php');	
	
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
		<?php include("menu.php"); ?>
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
			      
		      	<div class="pure-u-1 pure-u-sm-1-2">
			      	<div class="form-padding">
			      		<label>Date of Birth</label>
				      	<input class="pure-input-1" type="text" id="student_dob" name="student_dob" value="<?php if (isset($data['student_dob'])) {print $data['student_dob'];} ?>" required/>
					  	</div>
					  </div>
					  <div class="pure-u-1 pure-u-sm-1-2">
							<div class="form-padding">
								<label>Sex</label>
					      <select class="pure-input-1" name="student_sex" onkeypress="return noenter()" required>
					        <option value="">Select...</option>
					        <option value="Female" <?php if (isset($data['student_sex'])) {if ($data['student_sex'] == "Female") {print " selected";}} ?>>Female</option>
					        <option value="Male" <?php if (isset($data['student_sex'])) {if ($data['student_sex'] == "Male") {print " selected";}} ?>>Male</option>
					      </select>		
					    </div>    
			    	</div> 
			    	
					  <div class="pure-u-1 pure-u-sm-1-2">
				    	<div class="form-padding">
				    		<label>Student Email</label>
						    <input class="pure-input-1" name="student_email" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['student_email'])) {print $data['student_email'];} ?>">
							</div>
						</div>
				  <div class="pure-u-1 pure-u-sm-1-2">
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
						<div class="pure-u-1 button-home">
			     		<button class="gl-submit-button">Save and continue to contact <i class="fa fa-hand-o-right"></i></button>
			      	</div>
		      	</div>
		    </form>
		</div>
	</div>
	
	<?php include ('footer.php'); 
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