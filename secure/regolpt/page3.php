<?php 	
	$page = 3;
	include ('header.php');
		
	$relationship_contact = array("Mother","Father","Stepmother","Stepfather","Legal Guardian","Foster Parent"); 
	$relationship_emerg = array("Stepmother","Stepfather","Grandmother","Grandfather","Sibling","Aunt","Uncle","Family Friend"); 

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
	    <form novalidate class="pure-form pure-form-stacked" method="post" parsley-validate>
	      <div class="pure-g">
	      	<div class="pure-u-1">
		      		<p>Please ensure that this section provides all of the information necessary for parent/guardian contact in the event of an emergency.</p>
					</div>
					<div class="pure-u-1">	
	    			<div class="form-padding">
							<label><?php print $data['student_name_common']; ?> lives with...</label>
	        		<select class="pure-input-1" name="student_custody"  onKeyPress="return noenter()" required>
		            <option <?php if($data['student_custody']=='Both Parents (Single Home)'){print "selected";}?>>Both Parents (Single Home)</option>
		            <option <?php if($data['student_custody']=='Both Parents (Shared Custody, Two Homes)'){print "selected";}?>>Both Parents (Shared Custody, Two Homes)</option>
		            <option <?php if($data['student_custody']=='Mother'){print "selected";}?>>Mother</option>
		            <option <?php if($data['student_custody']=='Father'){print "selected";}?>>Father</option>
		            <option <?php if($data['student_custody']=='Other (Please Specify)'){print "selected";}?>>Other (Please Specify)</option>
		          </select>
		       </div>
	       	</div>
					<div class="pure-u-1">
						<div class="form-padding">
	          	<label>Details</label>
	          	<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_custody_details" id="student_custody_details"><?php if (isset($data['student_custody_details'])) {print $data['student_custody_details'];} ?></textarea>
		    			<hr />
		    		</div>
		    	</div>
	    		
	    		<!-- Primary Contact -->
	    		<div class="pure-u-1">
	    			<div class="form-padding">
		    			<p style="color: #326CAA;font-weight: 400;"><?php print $data['student_name_common']; ?>'s Primary Contact</p>
		    		</div> 
		    	</div>
	    		    
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
		       		<label>First Name</label>
					    <input class="pure-input-1" name="g1_name_first" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g1_name_first'])) {print $data['g1_name_first'];} ?>" required>
		        </div> 
		      </div>    
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
		    			<label>Last Name</label>
						  <input class="pure-input-1" name="g1_name_last" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g1_name_last'])) {print $data['g1_name_last'];} ?>" required>
		    		</div> 
		      </div>    
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
		        	<label>Relationship to <?php print $data['student_name_common']; ?></label>
		          <select class="pure-input-1" name="g1_relationship"  onKeyPress="return noenter()" required>
		         	<option value="">Please select</option>
							<?php
	              foreach($relationship_contact as $relationship)
	                {
	                    print "<option value=\"".$relationship."\"";
											if (isset($data['g1_relationship'])) {if ($data['g1_relationship'] == $relationship) {print " selected";}}
	                    print ">".$relationship."</option>";
	                }
	            ?>
	          	</select>
	          </div> 
		      </div>   
		       
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
	          	<label>Home Phone</label>
					    <input class="pure-input-1" name="g1_hp" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g1_hp'])) {print $data['g1_hp'];} ?>" parsley-error-message="Please enter a valid telephone number." required/>
						</div> 
		      </div>    
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
		    			<label>Cell Phone</label>
							<input class="pure-input-1" name="g1_cp" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g1_cp'])) {print $data['g1_cp'];} ?>" parsley-error-message="Please enter a valid telephone number.">
			      </div> 
		      </div>    
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
		    			<label>Work Phone</label>
						  <input class="pure-input-1" name="g1_wp" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g1_wp'])) {print $data['g1_wp'];} ?>" parsley-error-message="Please enter a valid telephone number.">
	  				</div> 
		      </div>    
		      
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
						  	<label>Summer Phone (Cottage)</label>
								<input class="pure-input-1" name="g1_sp" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g1_sp'])) {print $data['g1_sp'];} ?>" parsley-error-message="Please enter a valid telephone number.">
						</div> 
		      </div>    
	    		<div class="pure-u-1 pure-u-sm-2-3">
	    			<div class="form-padding">
		      		<label>Email</label>
							<input class="pure-input-1" name="g1_email" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g1_email'])) {print $data['g1_email'];} ?>" parsley-type="email">
			  		</div> 
		      </div>
		          
	    		<div class="pure-u-1">
	    			<div class="form-padding">
			      <label>Address</label>
						<input class="pure-input-1" name="g1_address" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g1_address'])) {print $data['g1_address'];} ?>">
				  </div> 
		      </div>    
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
				    <label>City</label>
						<input class="pure-input-1" name="g1_city" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g1_city'])) {print $data['g1_city'];} ?>">
				  </div> 
		      </div>    
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
				  	<label>Province</label>
				    <input class="pure-input-1" name="g1_province" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g1_province'])) {print $data['g1_province'];} ?>">
					</div> 
		      </div>    
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
				  	<label>Postal Code</label>
						<input class="pure-input-1" name="g1_postalcode" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g1_postalcode'])) {print $data['g1_postalcode'];} ?>">
					</div> 
		      </div>    
	    		<div class="pure-u-1">
	    			<div class="form-padding">
					  	<label>Contact Notes:</label>
					  	<label style="font-size: .75em;font-variant: normal;"><em>(Ex. Will be at the cottage while student is on trip.)</em></label>
							<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="g1_notes"><?php if (isset($data['g1_notes'])) {print $data['g1_notes'];} ?></textarea> 
							<hr />
						</div>	
					</div>				
				 	
				 	<!-- Secondary Contact -->
				 					 					 	<div class="pure-u-1">
	    			<div class="form-padding">
							<p style="color: #326CAA;font-weight: 400;"><?php print $data['student_name_common']; ?>'s Secondary Contact</p>
						</div>
					</div>
					<div class="pure-u-1">
						<p>This section is provided for the second parent/guardian or for a student staying at a location other than his/her regular home during the course, or for parents who may be staying at a separate address (cottage, etc.) during the course.</p>
					</div>
	    		<div class="pure-u-1 pure-u-sm-1-3">
	       		<div class="form-padding">
	       			<label>First Name</label>
					    <input class="pure-input-1" name="g2_name_first" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g2_name_first'])) {print $data['g2_name_first'];} ?>">
		        </div> 
		      </div>    
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
	    				<label>Last Name</label>
					    <input class="pure-input-1" name="g2_name_last" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g2_name_last'])) {print $data['g2_name_last'];} ?>">
	    			</div>
	    		</div>     
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
	    				<label>Relationship to <?php print $data['student_name_common']; ?></label>
		          <select class="pure-input-1" name="g2_relationship"  onKeyPress="return noenter()">
					   	<option value="">Please select</option>
							<?php
	              foreach($relationship_contact as $relationship)
	                {
	                    print "<option value=\"".$relationship."\"";
											if (isset($data['g2_relationship'])) {if ($data['g2_relationship'] == $relationship) {print " selected";}}
	                    print ">".$relationship."</option>";
	                }
	            ?>
	          	</select>
	        	</div>
	    		</div>   
	    		  
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">	
	          	<label>Home Phone</label>
					    <input class="pure-input-1" name="g2_hp" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g2_hp'])) {print $data['g2_hp'];} ?>" parsley-error-message="Please enter a valid telephone number.">
						</div>
	    		</div>     
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
	    				<label>Cell Phone</label>
							<input class="pure-input-1" name="g2_cp" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g2_cp'])) {print $data['g2_cp'];} ?>" parsley-error-message="Please enter a valid telephone number.">
		        </div>
	    		</div>     
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
		          <label>Work Phone</label>
					    <input class="pure-input-1" name="g2_wp" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g2_wp'])) {print $data['g2_wp'];} ?>" parsley-error-message="Please enter a valid telephone number.">
						</div>
	    		</div>    
	    		 
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
	    				<label>Summer Phone (Cottage)</label>
							<input class="pure-input-1" name="g2_sp" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g2_sp'])) {print $data['g2_sp'];} ?>" parsley-error-message="Please enter a valid telephone number.">
						</div>
	    		</div>     
	    		<div class="pure-u-1 pure-u-sm-2-3">
	    			<div class="form-padding">
	    				<label>Email</label>
							<input class="pure-input-1" name="g2_email" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g2_email'])) {print $data['g2_email'];} ?>" parsley-type="email">
			  		</div>
	    		</div>     
	    		
	    		<div class="pure-u-1">
	    			<div class="form-padding">
					  	<label>Contact Notes:</label>
							<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="g2_notes"><?php if (isset($data['g2_notes'])) {print $data['g2_notes'];} ?></textarea> 
							<hr />
						</div>
	    		</div>     
	    		
	    		<div class="pure-u-1">
	    			<div class="form-padding">
							<p style="color: #326CAA;font-weight: 400;"><?php print $data['student_name_common']; ?>'s Emergency Contact</p>
						</div>
					</div>
	    		<div class="pure-u-1">
	    			<div class="form-padding">
							<p>In the event of an emergency, the Gould Lake Outdoor Centre will attempt to contact the persons above.  Please 
							provide the name and contact information for an individual that the Gould Lake Outdoor Centre may contact in the event that parents/guardians are unavailable.
							In most cases, this is the name of a relative or family friend who lives in the area.</p>
	    			</div>
	    		</div>     
	    		
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
	    				<label>First Name</label>
						  <input class="pure-input-1" name="c_name_first" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['c_name_first'])) {print $data['c_name_first'];} ?>" required>				  	
						</div>
	    		</div>     
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
	    				<label>Last Name</label>
						  <input class="pure-input-1" name="c_name_last" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['c_name_last'])) {print $data['c_name_last'];} ?>" required>
	    			</div>
	    		</div>     
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
		        	<label>Relationship to <?php print $data['student_name_common']; ?></label>
		          <select class="pure-input-1"  class="pure-input-1" name="c_relationship"  onKeyPress="return noenter()" required>
			          <option value="">Please select</option>
								<?php               
		              foreach($relationship_emerg as $relationship)
		                {
		                    print "<option value=\"".$relationship."\"";
												if (isset($data['c_relationship'])) {if ($data['c_relationship'] == $relationship) {print " selected";}}
		                    print ">".$relationship."</option>";
		                }
		            ?>
	          	</select>
	        	</div>
	    		</div>     
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">
			       	<label>Home Phone</label>
						  <input class="pure-input-1" name="c_hp" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['c_hp'])) {print $data['c_hp'];} ?>" parsley-rangelength="[10,15]" parsley-error-message="Please enter a valid telephone number." required />
			  		</div>
	    		</div>     
	    		<div class="pure-u-1 pure-u-sm-1-3">
	    			<div class="form-padding">	
							<label>Cell Phone</label>
							<input class="pure-input-1" name="c_cp" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['c_cp'])) {print $data['c_cp'];} ?>" parsley-error-message="Please enter a valid telephone number.">
				  	</div>
				  </div> 
				  <div class="pure-u-1 button-home">
		     		<button class="gl-submit-button">Save and continue to health <i class="fa fa-hand-o-right"></i></button>
		      </div> 
				</div>
			</form>
		</div>
	</div>
	
	<?php include ('footer.php'); 
}
?>

<script>
   
	$(document).ready(function(){
	 	$(".gl-submit-button").click(function(){
			$(".pure-form").submit(function(e){
		  	e.preventDefault();
				if ( $(this).parsley().isValid() ) {
					$("#working").show();
					var senddata = $(this).serialize();
		   	 	//alert (senddata);return false;
		   	 	
		   	 	$.ajax({
		        type: "POST",
		        url: "process-3-contacts.php",
		        data: senddata,
		        success: function() {
							window.location.href = "page4.php";
	          }
	     	 	});	     	 	
	     	}
	 		});
	 	});	 
	 	
	 	$(".textarea_expand").on("keyup focus", function(){
      this.style.height = "1px";
      this.style.height = (this.scrollHeight) + "px"; 
    });	
	});
	
</script>