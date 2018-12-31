<?php 	
	$page = 3;
	include ('header.php');
		
	$relationship_contact = array("Mother","Father","Stepmother","Stepfather","Legal Guardian","Foster Parent","Other"); 
	$relationship_emerg = array("Stepmother","Stepfather","Grandparent","Sibling","Aunt","Uncle","Family Friend"); 
	$phone_types = array("Home", "Cell", "Work", "Other");
	
	if ($_SESSION['registration_id'] != 0) {
		$stmt = $conn->prepare("select * from ss_registrations where registration_id = :reg_id");
		$stmt -> bindValue(':reg_id', $_SESSION['registration_id']);
		$stmt-> execute();
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
	}

if ($_SESSION['registration_id'] == 0){header("Location: http://outed.limestone.on.ca/regfiles/");}	 
else {
?>
	<style>
		ul.parsley-error-list {
	        display:none !important;
	    }
	    ul.parsley-error-list li {
	        display:none !important;
	    }
    </style>
    
	<div id="gl-main">
		<?php include("menu.php"); ?>
		<div id="gl-wrapper" style="min-height: 100%">	
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
			    	<div class="pure-u-1 pure-u-sm-1-2 pure-u-sm-1-4">
		    			<div class="form-padding">
			       			<label>First Name</label>
						    <input class="pure-input-1" name="g1_name_first" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g1_name_first'])) {print $data['g1_name_first'];} ?>" required>
			        	</div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-1-2 pure-u-sm-1-4">
		    			<div class="form-padding">
			    			<label>Last Name</label>
							<input class="pure-input-1" name="g1_name_last" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g1_name_last'])) {print $data['g1_name_last'];} ?>" required>
			    		</div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-1-2 pure-u-sm-1-4">
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
			      	<div class="pure-u-1 pure-u-sm-1-2 pure-u-sm-1-4">
		    			<div class="form-padding">
			      			<label>Email</label>
							<input class="pure-input-1" name="g1_email" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g1_email'])) {print $data['g1_email'];} ?>" parsley-type="email">
				  		</div> 
			      	</div>
			       	<div class="pure-u-1 pure-u-sm-3-8">
		    			<div class="form-padding">
		          			<label>Phone #1 Type</label>
							<select class="pure-input-1" name="g1_p1_type" onKeyPress="return noenter()" required>
			         			<option value="">Please select</option>
								<?php
		              			foreach($phone_types as $phone_type)
		                		{
		                    		print "<option value=\"".$phone_type."\"";
									if (isset($data['g1_p1_type'])) {if ($data['g1_p1_type'] == $phone_type) {print " selected";}}
		                    		print ">".$phone_type."</option>";
		                		}
		            			?>
		          			</select>
		          		</div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-5-8">
		    			<div class="form-padding">
			    			<label>Phone #1</label>
							<div class="pure-g">
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g1_p1_1" type="text" placeholder="###" maxlength="3" onKeyPress="return noenter()" value="<?php if (isset($data['g1_p1'])) {print substr($data['g1_p1'],0,3);} ?>" data-parsley-length="[3, 3]" required>
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g1_p1_2" type="text" placeholder="###" maxlength="3" onKeyPress="return noenter()" value="<?php if (isset($data['g1_p1'])) {print substr($data['g1_p1'],4,3);} ?>" data-parsley-length="[3, 3]" required>
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g1_p1_3" type="text" placeholder="####" maxlength="4" onKeyPress="return noenter()" value="<?php if (isset($data['g1_p1'])) {print substr($data['g1_p1'],8,4);} ?>" data-parsley-length="[4, 4]" required>
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g1_p1_4" type="text" placeholder="EXT" onKeyPress="return noenter()" value="<?php if (isset($data['g1_p1'])) {print substr($data['g1_p1'],13);} ?>">
				      			</div>
				      		</div>
				      	</div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-3-8">
		    			<div class="form-padding">
		          			<label>Phone #2 Type</label>
						    <select class="pure-input-1" name="g1_p2_type" onKeyPress="return noenter()">
			         			<option value="">Please select</option>
								<?php
		              			foreach($phone_types as $phone_type)
		                		{
		                    		print "<option value=\"".$phone_type."\"";
									if (isset($data['g1_p2_type'])) {if ($data['g1_p1_type'] == $phone_type) {print " selected";}}
		                    		print ">".$phone_type."</option>";
		                		}
		            			?>
		          			</select>
		          		</div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-5-8">
		    			<div class="form-padding">
			    			<label>Phone #2</label>
			    			<div class="pure-g">
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g1_p2_1" type="text" placeholder="###" maxlength="3" onKeyPress="return noenter()" value="<?php if (isset($data['g1_p2'])) {print substr($data['g1_p2'],0,3);} ?>" data-parsley-minlength="3" data-parsley-maxlength="3">
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g1_p2_2" type="text" placeholder="###" maxlength="3" onKeyPress="return noenter()" value="<?php if (isset($data['g1_p2'])) {print substr($data['g1_p2'],4,3);} ?>" data-parsley-minlength="3" data-parsley-maxlength="3">
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g1_p2_3" type="text" placeholder="####" maxlength="4" onKeyPress="return noenter()" value="<?php if (isset($data['g1_p2'])) {print substr($data['g1_p2'],8,4);} ?>" data-parsley-minlength="4" data-parsley-maxlength="4">
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g1_p2_4" type="text" placeholder="EXT" onKeyPress="return noenter()" value="<?php if (isset($data['g1_p2'])) {print substr($data['g1_p2'],13);} ?>">
				      			</div>
				      		</div>
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
						<p>This section is provided for the second parent/guardian or for a student staying at a location other than his/her regular home
							 during the course, or for parents who may be staying at a separate address (cottage, etc.) 
							 during the course. If using <em>Other</em> in the <em>Relation to</em> dropdown, please specify the relationship
							 to the student in the <em>Contact Notes</em> section (ie. Family Friend).</p>
					</div>
		    		<div class="pure-u-1 pure-u-sm-1-2 pure-u-sm-1-4">
			       		<div class="form-padding">
			       			<label>First Name</label>
							    <input class="pure-input-1" name="g2_name_first" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g2_name_first'])) {print $data['g2_name_first'];} ?>">
				        </div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-1-2 pure-u-sm-1-4">
		    			<div class="form-padding">
		    				<label>Last Name</label>
						    <input class="pure-input-1" name="g2_name_last" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g2_name_last'])) {print $data['g2_name_last'];} ?>">
		    			</div>
		    		</div>     
		    		<div class="pure-u-1 pure-u-sm-1-2 pure-u-sm-1-4">
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
		    		<div class="pure-u-1 pure-u-sm-1-2 pure-u-sm-1-4">
		    			<div class="form-padding">
		    				<label>Email</label>
							<input class="pure-input-1" name="g2_email" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g2_email'])) {print $data['g2_email'];} ?>" parsley-type="email">
				  		</div>
		    		</div> 
		    		<div class="pure-u-1 pure-u-sm-3-8">
		    			<div class="form-padding">
		          			<label>Phone #1 Type</label>
							<select class="pure-input-1" name="g2_p1_type" onKeyPress="return noenter()">
			         			<option value="">Please select</option>
								<?php
		              			foreach($phone_types as $phone_type)
		                		{
		                    		print "<option value=\"".$phone_type."\"";
									if (isset($data['g2_p1_type'])) {if ($data['g2_p1_type'] == $phone_type) {print " selected";}}
		                    		print ">".$phone_type."</option>";
		                		}
		            			?>
		          			</select>
		          		</div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-5-8">
		    			<div class="form-padding">
			    			<label>Phone #1</label>
							<div class="pure-g">
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g2_p1_1" type="text" placeholder="###" maxlength="3" onKeyPress="return noenter()" value="<?php if (isset($data['g2_p1'])) {print substr($data['g2_p1'],0,3);} ?>" data-parsley-minlength="3" data-parsley-maxlength="3">
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g2_p1_2" type="text" placeholder="###" maxlength="3" onKeyPress="return noenter()" value="<?php if (isset($data['g2_p1'])) {print substr($data['g2_p1'],4,3);} ?>" data-parsley-minlength="3" data-parsley-maxlength="3">
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g2_p1_3" type="text" placeholder="####" maxlength="4" onKeyPress="return noenter()" value="<?php if (isset($data['g2_p1'])) {print substr($data['g2_p1'],8,4);} ?>" data-parsley-minlength="4" data-parsley-maxlength="4">
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g2_p1_4" type="text" placeholder="EXT" onKeyPress="return noenter()" value="<?php if (isset($data['g2_p1'])) {print substr($data['g2_p1'],13);} ?>">
				      			</div>
							</div>
						</div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-3-8">
		    			<div class="form-padding">
		          			<label>Phone #2 Type</label>
						    <select class="pure-input-1" name="g2_p2_type" onKeyPress="return noenter()">
			         			<option value="">Please select</option>
								<?php
		              			foreach($phone_types as $phone_type)
		                		{
		                    		print "<option value=\"".$phone_type."\"";
									if (isset($data['g2_p2_type'])) {if ($data['g2_p2_type'] == $phone_type) {print " selected";}}
		                    		print ">".$phone_type."</option>";
		                		}
		            			?>
		          			</select>
		          		</div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-5-8">
		    			<div class="form-padding">
			    			<label>Phone #2</label>
							<div class="pure-g">
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g2_p2_1" type="text" placeholder="###" maxlength="3" onKeyPress="return noenter()" value="<?php if (isset($data['g2_p2'])) {print substr($data['g2_p2'],0,3);} ?>" data-parsley-minlength="3" data-parsley-maxlength="3">
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g2_p2_2" type="text" placeholder="###" maxlength="3" onKeyPress="return noenter()" value="<?php if (isset($data['g2_p2'])) {print substr($data['g2_p2'],4,3);} ?>" data-parsley-minlength="3" data-parsley-maxlength="3">
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g2_p2_3" type="text" placeholder="####" maxlength="4" onKeyPress="return noenter()" value="<?php if (isset($data['g2_p2'])) {print substr($data['g2_p2'],8,4);} ?>" data-parsley-minlength="4" data-parsley-maxlength="4">
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="g2_p2_4" type="text" placeholder="EXT" onKeyPress="return noenter()" value="<?php if (isset($data['g2_p2'])) {print substr($data['g2_p2'],13);} ?>">
					      		</div>
					      	</div>
			      		</div> 
			      	</div>  
		    		<div class="pure-u-1">
		    			<div class="form-padding">
				      		<label>Address</label>
							<input class="pure-input-1" name="g2_address" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g2_address'])) {print $data['g2_address'];} ?>">
					  	</div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-1-3">
		    			<div class="form-padding">
					    	<label>City</label>
							<input class="pure-input-1" name="g2_city" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g2_city'])) {print $data['g2_city'];} ?>">
					  	</div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-1-3">
		    			<div class="form-padding">
						  	<label>Province</label>
						    <input class="pure-input-1" name="g2_province" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g2_province'])) {print $data['g2_province'];} ?>">
						</div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-1-3">
		    			<div class="form-padding">
					  		<label>Postal Code</label>
							<input class="pure-input-1" name="g2_postalcode" type="text" onKeyPress="return noenter()" value="<?php if (isset($data['g2_postalcode'])) {print $data['g2_postalcode'];} ?>">
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
							provide the name and contact information for an individual or household that the Gould Lake Outdoor Centre may contact in the event that parents/guardians are unavailable.
							In most cases, this is the name of a relative or family friend who lives in the area.</p>
		    			</div>
		    		</div>     
		    		<div class="pure-u-1 pure-u-sm-1-3">
		    			<div class="form-padding">
		    				<label>First Name (s)</label>
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
		    		<div class="pure-u-1 pure-u-sm-3-8">
		    			<div class="form-padding">
		          			<label>Phone #1 Type</label>
							<select class="pure-input-1" name="c_p1_type" onKeyPress="return noenter()" required>
			         			<option value="">Please select</option>
								<?php
		              			foreach($phone_types as $phone_type)
		                		{
		                    		print "<option value=\"".$phone_type."\"";
									if (isset($data['c_p1_type'])) {if ($data['c_p1_type'] == $phone_type) {print " selected";}}
		                    		print ">".$phone_type."</option>";
		                		}
		            			?>
		          			</select>
		          		</div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-5-8">
		    			<div class="form-padding">
			    			<label>Phone #1</label>
							<div class="pure-g">
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="c_p1_1" type="text" placeholder="###" maxlength="3" onKeyPress="return noenter()" value="<?php if (isset($data['c_p1'])) {print substr($data['c_p1'],0,3);} ?>" data-parsley-minlength="3" data-parsley-maxlength="3" required>
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="c_p1_2" type="text" placeholder="###" maxlength="3" onKeyPress="return noenter()" value="<?php if (isset($data['c_p1'])) {print substr($data['c_p1'],4,3);} ?>" data-parsley-minlength="3" data-parsley-maxlength="3" required>
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="c_p1_3" type="text" placeholder="####" maxlength="4"onKeyPress="return noenter()" value="<?php if (isset($data['c_p1'])) {print substr($data['c_p1'],8,4);} ?>" data-parsley-minlength="4" data-parsley-maxlength="4" required>
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="c_p1_4" type="text" placeholder="EXT" onKeyPress="return noenter()" value="<?php if (isset($data['c_p1'])) {print substr($data['c_p1'],13);} ?>">
				      			</div>
				      		</div>
				      	</div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-3-8">
		    			<div class="form-padding">
		          			<label>Phone #2 Type</label>
						    <select class="pure-input-1" name="c_p2_type" onKeyPress="return noenter()">
			         			<option value="">Please select</option>
								<?php
		              			foreach($phone_types as $phone_type)
		                		{
		                    		print "<option value=\"".$phone_type."\"";
									if (isset($data['c_p2_type'])) {if ($data['c_p2_type'] == $phone_type) {print " selected";}}
		                    		print ">".$phone_type."</option>";
		                		}
		            			?>
		          			</select>
		          		</div> 
			      	</div>    
		    		<div class="pure-u-1 pure-u-sm-5-8">
		  				<div class="form-padding">	    			
		  					<label>Phone #2</label>
							<div class="pure-g">
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="c_p2_1" type="text" placeholder="###" maxlength="3" onKeyPress="return noenter()" value="<?php if (isset($data['c_p2'])) {print substr($data['c_p2'],0,3);} ?>">
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="c_p2_2" type="text" placeholder="###" maxlength="3" onKeyPress="return noenter()" value="<?php if (isset($data['c_p2'])) {print substr($data['c_p2'],4,3);} ?>" >
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="c_p2_3" type="text" placeholder="####" maxlength="4" onKeyPress="return noenter()" value="<?php if (isset($data['c_p2'])) {print substr($data['c_p2'],8,4);} ?>" >
								</div>
								<div class="pure-u-1-4">
									<input style="width: 100%;" name="c_p2_4" type="text" placeholder="EXT" onKeyPress="return noenter()" value="<?php if (isset($data['c_p2'])) {print substr($data['c_p2'],13);} ?>">
				       			</div>
							</div>
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