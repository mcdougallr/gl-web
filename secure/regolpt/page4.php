<?php 	
	$page = 4;
	include ('header.php');
		
	if ($_SESSION['registration_id'] != 0) {
		$stmt = $conn->prepare("select * from gl_registrations where registration_id = :reg_id");
		$stmt -> bindValue(':reg_id', $_SESSION['registration_id']);
		$stmt-> execute();
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
	}

if ($_SESSION['registration_id'] == 0){header("Location: http://outed.limestone.on.ca/regfiles/");}	 
else {
?>
	<div id="gl-main">
		<div id="gl-wrapper">
			<form novalidate class="pure-form pure-form-stacked" method="post" parsley-validate parsley-required-message="">
				<div class="pure-g">
					<div class="pure-u-1">
						<p>Gould Lake activities can be strenuous and are often more physically challenging than some 
							participants are used to.  We do not want any student to engage in activities that would be 
							detrimental to their health, or which would be opposed by their doctor because of recent illness, 
							injury or surgery.  We must be aware of all health conditions which might affect the progress 
							or welfare of the student or other students while on this course.  Health conditions do not necessarily preclude 
							students from participating in programs, but should be explained clearly in the details section below.</p>
						<hr />
					</div>	  		      	
					<div class="pure-u-1 pure-u-sm-1-2">
						<div class="form-padding">
							<label>Health Card Number</label>																																												
					    <input class="pure-input-1" type="text" name="student_health_card" onKeyPress="return noenter()" value="<?php if (isset($data['student_health_card'])) {print $data['student_health_card'];}?>" />
					  </div>
					</div>
					<div class="pure-u-1 pure-u-sm-1-2">
						<div class="form-padding">
					 	 	<label>Date of Last Tetanus Shot</label>
		          <input class="pure-input-1" type="text" id="student_health_tetanus" name="student_health_tetanus" onkeypress="return noenter()" value="<?php if (isset($data['student_health_tetanus'])) {print $data['student_health_tetanus'];}?>" />
						</div>
	  			</div>
					<div class="pure-u-1 pure-u-sm-1-2">
						<div class="form-padding">
					   	<label>Family Doctor</label>
					   	<input  class="pure-input-1" name="student_health_doctor" type="text" onKeyPress="return noenter()" value="<?php print $data['student_health_doctor']; ?>">
					</div>
	  			</div>
					<div class="pure-u-1 pure-u-sm-1-2">
						<div class="form-padding">
							<label>Doctor's Office Phone Number</label>
						  <input class="pure-input-1" name="student_health_doctorphone" type="text" onKeyPress="return noenter()" value="<?php print $data['student_health_doctorphone']; ?>" parsely-type="phone">
						</div>
					</div>
			   	<div class="pure-u-1">
			   		<hr />
			   		<div class="form-padding-2">
			   			<div class="pure-g">
			   				<div style="margin: 5px 0;" class="pure-u-1 pure-u-sm-3-4">
									<label style="display: inline;">Has <?php print $data['student_name_common']; ?> ever been hospitalized?</label>
								</div>
								<div class="pure-u-1 pure-u-sm-1-4 input-radio-align">
									<input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_hospitalized" type="radio" value="Yes" <?php if (isset($data['student_health_hospitalized'])) {if ($data['student_health_hospitalized'] == "Yes") {print " checked";}} ?> required>
						      <label style="display: inline;">Yes</label>
						      <input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_hospitalized" type="radio" value="No" <?php if (isset($data['student_health_hospitalized'])) {if ($data['student_health_hospitalized'] == "No") {print " checked";}} ?> required>
									<label style="display: inline;">No</label>
								</div>
							</div>
							<div class="med_toggle" id="student_health_hospitalized_div">
								<label style="font-size: .9em;color:#a93a3c;font-variant: normal;">Details...(How long ago? Are there on-going related issues?)</label>
								<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_health_hospitalized_details"><?php if (isset($data['student_health_hospitalized_details'])) {print $data['student_health_hospitalized_details'];} ?></textarea>
							</div>
						</div>
						<hr />
						<div class="form-padding-2">
							<div class="pure-g">
			   				<div style="margin: 5px 0;" class="pure-u-1 pure-u-sm-3-4">
									<label style="display: inline;">Has <?php print $data['student_name_common']; ?> suffered any physical activity related injuries in the past year? (Breaks/sprains/strains, concussions, dislocations...)</label>
					    	</div>
								<div class="pure-u-1 pure-u-sm-1-4 input-radio-align">
									<input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_injuries" type="radio" value="Yes" <?php if (isset($data['student_health_injuries'])) {if ($data['student_health_injuries'] == "Yes") {print " checked";}} ?>  required>
						      <label style="display: inline;">Yes</label>
						      <input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_injuries" type="radio" value="No" <?php if (isset($data['student_health_injuries'])) {if ($data['student_health_injuries'] == "No") {print " checked";}} ?>  required>
									<label style="display: inline;">No</label>
								</div>
							</div>
							<div class="med_toggle" id="student_health_injuries_div">
					    	 <label style="font-size: .9em;color:#a93a3c;font-variant: normal;">Details...</label>
					    	 <textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_health_injuries_details"><?php print $data['student_health_injuries_details']; ?></textarea>
					    </div>
					  </div>  
						<hr />
					  <div class="form-padding-2">  
					  	<div class="pure-g">
			   				<div style="margin: 5px 0;" class="pure-u-1 pure-u-sm-3-4">
									<label style="display: inline;">Does <?php print $data['student_name_common']; ?> take regular medication?</label>
					    	</div>
								<div class="pure-u-1 pure-u-sm-1-4 input-radio-align">
									<input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_meds" type="radio" value="Yes" <?php if (isset($data['student_health_meds'])) {if ($data['student_health_meds'] == "Yes") {print " checked";}} ?>  required>
						      <label style="display: inline;">Yes</label>
						      <input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_meds" type="radio" value="No" <?php if (isset($data['student_health_meds'])) {if ($data['student_health_meds'] == "No") {print " checked";}} ?>  required>
									<label style="display: inline;">No</label>
								</div>
							</div>						
					    <div class="med_toggle" id="student_health_meds_div">
								<label style="font-size: .9em;color:#a93a3c;font-variant: normal;">Details...(Name of medication? Reason for medication? Dosage? Side-effect?)</label>
								<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_health_meds_details"><?php if (isset($data['student_health_meds_details'])) {print $data['student_health_hospitalized_details'];} ?></textarea>
							</div>
						</div>
						<hr />
						<div class="form-padding-2">  
							<div class="pure-g">
			   				<div style="margin: 5px 0;" class="pure-u-1 pure-u-sm-3-4">
					    		<label style="display: inline;">Does <?php print $data['student_name_common']; ?> have allergies (seasonal, peanuts, bees, etc.)</label>
					    	</div>
								<div class="pure-u-1 pure-u-sm-1-4 input-radio-align">
									<input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_allergies" type="radio" value="Yes" <?php if (isset($data['student_health_allergies'])) {if ($data['student_health_allergies'] == "Yes") {print " checked";}} ?>  required>
						      <label style="display: inline;">Yes</label>
						      <input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_allergies" type="radio" value="No" <?php if (isset($data['student_health_allergies'])) {if ($data['student_health_allergies'] == "No") {print " checked";}} ?>  required>
									<label style="display: inline;">No</label>
								</div>
							</div>	
					    <div class="med_toggle" id="student_health_allergies_div">
								<label style="font-size: .9em;color:#a93a3c;font-variant: normal;">Details...(Allergy type? Severity? Last reaction? Related medications?)</label>
								<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_health_allergies_details"><?php if (isset($data['student_health_allergies_details'])) {print $data['student_health_allergies_details'];} ?></textarea>
							</div>
						</div>
						<hr />
						<div class="form-padding-2">  
							<div class="pure-g">
			   				<div style="margin: 5px 0;" class="pure-u-1 pure-u-sm-3-4">
					    		<label style="display: inline;">Does <?php print $data['student_name_common']; ?> carry an epipen?</label>
					    	</div>
								<div class="pure-u-1 pure-u-sm-1-4 input-radio-align">
									<input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_epipen" type="radio" value="Yes" <?php if (isset($data['student_health_epipen'])) {if ($data['student_health_epipen'] == "Yes") {print " checked";}} ?>  required>
						      <label style="display: inline;">Yes</label>
						      <input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_epipen" type="radio" value="No" <?php if (isset($data['student_health_epipen'])) {if ($data['student_health_epipen'] == "No") {print " checked";}} ?>  required>
									<label style="display: inline;">No</label>
								</div>
							</div>	
					    <div class="med_toggle" id="student_health_epipen_div">
								<label style="font-size: .9em;color:#a93a3c;font-variant: normal;">Details...(Date of last reaction? Date of last testing?)</label>
								<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_health_epipen_details"><?php if (isset($data['student_health_epipen_details'])) {print $data['student_health_epipen_details'];} ?></textarea>
							</div>	
						</div>
						<hr />
			    	<div class="form-padding-2">  
			    		<div class="pure-g">
			   				<div style="margin: 5px 0;" class="pure-u-1 pure-u-sm-3-4">
					    		<label style="display: inline;">Does <?php print $data['student_name_common']; ?> have asthma?</label>
					    	</div>
								<div class="pure-u-1 pure-u-sm-1-4 input-radio-align">
									<input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_asthma" type="radio" value="Yes" <?php if (isset($data['student_health_asthma'])) {if ($data['student_health_asthma'] == "Yes") {print " checked";}} ?>  required>
						      <label style="display: inline;">Yes</label>
						      <input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_asthma" type="radio" value="No" <?php if (isset($data['student_health_asthma'])) {if ($data['student_health_asthma'] == "No") {print " checked";}} ?>  required>
									<label style="display: inline;">No</label>
								</div>
							</div>	
					    <div class="med_toggle" id="student_health_asthma_div">
								<label style="font-size: .9em;color:#a93a3c;font-variant: normal;">Details...(Related medications? Severity? Triggers?)</label>
								<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_health_asthma_details"><?php if (isset($data['student_health_asthma_details'])) {print $data['student_health_asthma_details'];} ?></textarea>
							</div>
						</div>
						<hr />
						<div class="form-padding-2">  
							<div class="pure-g">
			   				<div style="margin: 5px 0;" class="pure-u-1 pure-u-sm-3-4">
									<label style="display: inline;">Has <?php print $data['student_name_common']; ?> ever suffered from epilepsy?</label>
					    	</div>
								<div class="pure-u-1 pure-u-sm-1-4 input-radio-align">
									<input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_epilepsy" type="radio" value="Yes" <?php if (isset($data['student_health_epilepsy'])) {if ($data['student_health_epilepsy'] == "Yes") {print " checked";}} ?>  required>
						      <label style="display: inline;">Yes</label>
						      <input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_epilepsy" type="radio" value="No" <?php if (isset($data['student_health_epilepsy'])) {if ($data['student_health_epilepsy'] == "No") {print " checked";}} ?>  required>
									<label style="display: inline;">No</label>	
								</div>
							</div>
					    <div class="med_toggle" id="student_health_epilepsy_div">
								<label style="font-size: .9em;color:#a93a3c;font-variant: normal;">Details...(Date of last seizure? Triggers?)</label>
								<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_health_epilepsy_details"><?php if (isset($data['student_health_epilepsy_details'])) {print $data['student_health_epilepsy_details'];} ?></textarea>
							</div>
						</div>
						<hr />
					  <div class="form-padding-2">  
					  	<div class="pure-g">
			   				<div style="margin: 5px 0;" class="pure-u-1 pure-u-sm-3-4">
					    		<label style="display: inline;">Does <?php print $data['student_name_common']; ?> have diabetes?</label>
					    	</div>
								<div class="pure-u-1 pure-u-sm-1-4 input-radio-align">
									<input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_diabetes" type="radio" value="Yes" <?php if (isset($data['student_health_diabetes'])) {if ($data['student_health_diabetes'] == "Yes") {print " checked";}} ?>  required>
						      <label style="display: inline;">Yes</label>
						      <input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_diabetes" type="radio" value="No" <?php if (isset($data['student_health_diabetes'])) {if ($data['student_health_diabetes'] == "No") {print " checked";}} ?>  required>
									<label style="display: inline;">No</label>
								</div>
							</div>	  
							<div class="med_toggle" id="student_health_diabetes_div">
								<label style="font-size: .9em;color:#a93a3c;font-variant: normal;">Details...</label>
								<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_health_diabetes_details"><?php if (isset($data['student_health_diabetes_details'])) {print $data['student_health_diabetes_details'];} ?></textarea>
							</div>
						</div>
						<hr />
					  <div class="form-padding-2">  
					  	<div class="pure-g">
			   				<div style="margin: 5px 0;" class="pure-u-1 pure-u-sm-3-4">
					    		<label style="display: inline;">Has <?php print $data['student_name_common']; ?> seen a counselor or therapist for psychological or emotional reasons within the last two years?</label>
					    	</div>
								<div class="pure-u-1 pure-u-sm-1-4 input-radio-align">
									<input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_counselor" type="radio" value="Yes" <?php if (isset($data['student_health_counselor'])) {if ($data['student_health_counselor'] == "Yes") {print " checked";}} ?>  required>
						      <label style="display: inline;">Yes</label>
						      <input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_counselor" type="radio" value="No" <?php if (isset($data['student_health_counselor'])) {if ($data['student_health_counselor'] == "No") {print " checked";}} ?>  required>
									<label style="display: inline;">No</label>
								</div>
							</div>
					    <div class="med_toggle" id="student_health_counselor_div">
								<label style="font-size: .9em;color:#a93a3c;font-variant: normal;">Details...</label>
								<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_health_counselor_details"><?php if (isset($data['student_health_counselor_details'])) {print $data['student_health_counselor_details'];} ?></textarea>
							</div>
						</div>
						<hr />
					  <div class="form-padding-2">  
					  	<div class="pure-g">
			   				<div style="margin: 5px 0;" class="pure-u-1 pure-u-sm-3-4">
					    		<label style="display: inline;">Does <?php print $data['student_name_common']; ?> suffer from anxiety or homesickness?</label>
					    	</div>
								<div class="pure-u-1 pure-u-sm-1-4 input-radio-align">
									<input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_anxiety" type="radio" value="Yes" <?php if (isset($data['student_health_anxiety'])) {if ($data['student_health_anxiety'] == "Yes") {print " checked";}} ?>  required>
						      <label style="display: inline;">Yes</label>
						      <input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_anxiety" type="radio" value="No" <?php if (isset($data['student_health_anxiety'])) {if ($data['student_health_anxiety'] == "No") {print " checked";}} ?>  required>
									<label style="display: inline;">No</label>
								</div>
							</div>  
					    <div class="med_toggle" id="student_health_anxiety_div">
								<label style="font-size: .9em;color:#a93a3c;font-variant: normal;">Details...</label>
								<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_health_anxiety_details"><?php if (isset($data['student_health_anxiety_details'])) {print $data['student_health_anxiety_details'];} ?></textarea>
							</div>
						</div>
						<hr />
						<div class="form-padding-2">  
							<div class="pure-g">
			   				<div style="margin: 5px 0;" class="pure-u-1 pure-u-sm-3-4">
					    		<label style="display: inline;">Are there any other limitations on <?php print $data['student_name_common']; ?>'s activities?</label>
					    	</div>
								<div class="pure-u-1 pure-u-sm-1-4 input-radio-align">
									<input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_limitations" type="radio" value="Yes" <?php if (isset($data['student_health_limitations'])) {if ($data['student_health_limitations'] == "Yes") {print " checked";}} ?>  required>
						      <label style="display: inline;">Yes</label>
						      <input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_limitations" type="radio" value="No" <?php if (isset($data['student_health_limitations'])) {if ($data['student_health_limitations'] == "No") {print " checked";}} ?>  required>
									<label style="display: inline;">No</label> 	
								</div>
							</div>
					    <div class="med_toggle" id="student_health_limitations_div">
								<label style="font-size: .9em;color:#a93a3c;font-variant: normal;">Details...</label>
								<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_health_limitations_details"><?php if (isset($data['student_health_limitations_details'])) {print $data['student_health_limitations_details'];} ?></textarea>
							</div>
						</div>
						<hr />
					  <div class="form-padding-2">  
					  	<div class="pure-g">
			   				<div style="margin: 5px 0;" class="pure-u-1 pure-u-sm-3-4">
					    		<label style="display: inline;">Are there any other conditions that might affect <?php print $data['student_name_common']; ?>'s health or the well being of others?</label>
					    	</div>
								<div class="pure-u-1 pure-u-sm-1-4 input-radio-align">
									<input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_others" type="radio" value="Yes" <?php if (isset($data['student_health_others'])) {if ($data['student_health_others'] == "Yes") {print " checked";}} ?>  required>
						      <label style="display: inline;">Yes</label>
						      <input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_health_others" type="radio" value="No" <?php if (isset($data['student_health_others'])) {if ($data['student_health_others'] == "No") {print " checked";}} ?>  required>
									<label style="display: inline;">No</label>  
								</div>
							</div>
					    <div class="med_toggle" id="student_health_others_div">
								<label style="font-size: .9em;color:#a93a3c;font-variant: normal;">Details...</label>
								<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_health_others_details"><?php if (isset($data['student_health_others_details'])) {print $data['student_health_others_details'];} ?></textarea>
							</div>
						</div>	
						<hr />
					  <div class="form-padding-2">	
					   	<label style="display: inline;">Tell us about <?php print $data['student_name_common']; ?>'s eating habits and dietary restrictions? (Vegetarian, allergies, picky eater, big eater...)</label>
					    <textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_health_dietary_details"><?php print $data['student_health_dietary_details']; ?></textarea>	   	
					  </div>
					</div>
					<div class="pure-u-1 button-home">
			     	<button style="padding-bottom: 0px;" class="gl-submit-button">Save and continue to additional <i class="fa fa-hand-o-right"></i></button>
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
	$('#student_health_tetanus').datepicker({
   	yearRange: "1993:2013",
   	dateFormat: 'yy-mm-dd',
   	changeMonth: true,
		changeYear: true,
		changeDay:false
		}); 
 })();
   
 $(document).ready(function(){
 	$(".textarea_expand").on("keyup", function(){
    this.style.height = "1px";
    this.style.height = (this.scrollHeight) + "px"; 
 	});	
	 
	if ($("input[name=student_health_hospitalized]:checked").val() == "Yes") {$('#student_health_hospitalized_div').show();}
	if ($("input[name=student_health_meds]:checked").val() == "Yes") {$('#student_health_meds_div').show();}
	if ($("input[name=student_health_allergies]:checked").val() == "Yes") {$('#student_health_allergies_div').show();}
	if ($("input[name=student_health_asthma]:checked").val() == "Yes") {$('#student_health_asthma_div').show();}
	if ($("input[name=student_health_epipen]:checked").val() == "Yes") {$('#student_health_epipen_div').show();}
	if ($("input[name=student_health_epilepsy]:checked").val() == "Yes") {$('#student_health_epilepsy_div').show();}
	if ($("input[name=student_health_diabetes]:checked").val() == "Yes") {$('#student_health_diabetes_div').show();}
	if ($("input[name=student_health_counselor]:checked").val() == "Yes") {$('#student_health_counselor_div').show();}
	if ($("input[name=student_health_anxiety]:checked").val() == "Yes") {$('#student_health_anxiety_div').show();}
	if ($("input[name=student_health_injuries]:checked").val() == "Yes") {$('#student_health_injuries_div').show();}
	if ($("input[name=student_health_limitations]:checked").val() == "Yes") {$('#student_health_limitations_div').show();}
	if ($("input[name=student_health_others]:checked").val() == "Yes") {$('#student_health_others_div').show();}	
	if ($("input[name=student_health_dietary]:checked").val() == "Yes") {$('#student_health_dietary_div').show();}
 	 	
	$(".pure-form").on('change', function() {
		if ($("input[name=student_health_hospitalized]:checked").val() == "Yes") {$('#student_health_hospitalized_div').show();}
		if ($("input[name=student_health_hospitalized]:checked").val() == "No") {$('#student_health_hospitalized_div').hide();}

		if ($("input[name=student_health_meds]:checked").val() == "Yes") {$('#student_health_meds_div').show();}
		if ($("input[name=student_health_meds]:checked").val() == "No") {$('#student_health_meds_div').hide();}

		if ($("input[name=student_health_allergies]:checked").val() == "Yes") {$('#student_health_allergies_div').show();}
		if ($("input[name=student_health_allergies]:checked").val() == "No") {$('#student_health_allergies_div').hide();}

		if ($("input[name=student_health_asthma]:checked").val() == "Yes") {$('#student_health_asthma_div').show();}
		if ($("input[name=student_health_asthma]:checked").val() == "No") {$('#student_health_asthma_div').hide();}

		if ($("input[name=student_health_epipen]:checked").val() == "Yes") {$('#student_health_epipen_div').show();}
		if ($("input[name=student_health_epipen]:checked").val() == "No") {$('#student_health_epipen_div').hide();}

		if ($("input[name=student_health_epilepsy]:checked").val() == "Yes") {$('#student_health_epilepsy_div').show();}
		if ($("input[name=student_health_epilepsy]:checked").val() == "No") {$('#student_health_epilepsy_div').hide();}

		if ($("input[name=student_health_diabetes]:checked").val() == "Yes") {$('#student_health_diabetes_div').show();}
		if ($("input[name=student_health_diabetes]:checked").val() == "No") {$('#student_health_diabetes_div').hide();}
		
		if ($("input[name=student_health_counselor]:checked").val() == "Yes") {$('#student_health_counselor_div').show();}
		if ($("input[name=student_health_counselor]:checked").val() == "No") {$('#student_health_counselor_div').hide();}

		if ($("input[name=student_health_anxiety]:checked").val() == "Yes") {$('#student_health_anxiety_div').show();}
		if ($("input[name=student_health_anxiety]:checked").val() == "No") {$('#student_health_anxiety_div').hide();}

		if ($("input[name=student_health_injuries]:checked").val() == "Yes") {$('#student_health_injuries_div').show();}
		if ($("input[name=student_health_injuries]:checked").val() == "No") {$('#student_health_injuries_div').hide();}

		if ($("input[name=student_health_limitations]:checked").val() == "Yes") {$('#student_health_limitations_div').show();}
		if ($("input[name=student_health_limitations]:checked").val() == "No") {$('#student_health_limitations_div').hide();}

		if ($("input[name=student_health_others]:checked").val() == "Yes") {$('#student_health_others_div').show();}
		if ($("input[name=student_health_others]:checked").val() == "No") {$('#student_health_others_div').hide();}			
		
		if ($("input[name=student_health_dietary]:checked").val() == "Yes") {$('#student_health_dietary_div').show();}
		if ($("input[name=student_health_dietary]:checked").val() == "No") {$('#student_health_dietary_div').hide();}				
	});

	$(".gl-submit-button").click(function(){
		$(".pure-form").submit(function(e){
	  	e.preventDefault();
			if ( $(this).parsley().isValid() ) {
				$("#working").show();
				var senddata = $(this).serialize();
	   	 	//alert (senddata);return false;
	   	 	
	   	 	$.ajax({
	        type: "POST",
	        url: "process-4-medical.php",
	        data: senddata,
	        success: function() {
						window.location.href = "page5.php";
          }
     	 	});	     	 	
     	}
 		});
 	});	 

});

</script>