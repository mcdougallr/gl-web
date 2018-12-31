<?php
	include ('sch-header.php');
?>

<link rel="stylesheet" href="_gl-school.css">

<?php			
if (isset($_SESSION['school_id'])) 
{
	$stmt = $conn->prepare("SELECT * FROM sy_schools WHERE school_id = :school_id");
	$stmt->bindValue(':school_id', $_SESSION['school_id']);
	$stmt->execute();
	$school = $stmt->fetch(PDO::FETCH_ASSOC);

	?>
	<h1><?php print $school['school_name']; ?></h1>
	<div class="pure-g">			  	
	  	<div class="pure-u-1 pure-u-md-1-4">
            <div id="school-menu">
                <a href="index.php" class="school-menu-button" value="schedule"><i class="fa fa-calendar menu-icon"></i> scheduled classes</a>
                <a href="booking.php" class="school-menu-button" value="booking"><i class="fa fa-pencil menu-icon"></i> book classes</a>
                <a href="http://outed.limestone.on.ca/aboutus/contactfind.php" class="school-menu-button" value="directions"><i class="fa fa-map-marker menu-icon"></i> directions to gl</a>
                <a href="http://outed.limestone.on.ca/teachers/programsheet.pdf" class="school-menu-button" value="info"><i class="fa fa-info menu-icon"></i> program info</a>
                <a href="../shared/logout.php" class="school-menu-button" value="logout"><i class="fa fa-sign-out menu-icon"></i> logout</a>
            </div>
		</div>
        <div class="pure-u-1 pure-u-md-3-4">	    
        	<?php 
				
				$today_date = date('Y-m-d');
				
				//Fetch upcoming visits from database
				$stmt = $conn->prepare("SELECT * FROM schedule_events
										LEFT JOIN schedule_visit ON schedule_visit.visit_id = schedule_events.event_type_id  
										LEFT JOIN sy_schools ON sy_schools.school_id = schedule_visit.school_id
										LEFT JOIN sy_programs ON sy_programs.program_id = schedule_visit.program_id
									   	WHERE event_type = 'V' AND schedule_visit.school_id = :school_id AND event_date >= :event_date AND event_date != '0000-00-00' AND visit_confirm = 1
									   	ORDER BY event_date");
				$stmt->bindValue(':school_id', $school['school_id']);
				$stmt->bindValue(':event_date', $today_date);
				$stmt->execute();
				$visits = $stmt->fetchAll();
				
				$visitcount = count($visits);
				?>
				
				<h2>Upcoming Scheduled Visits</h2>
				
				<?php if ($visitcount > 0) 
				{ ?>	
				    <table class="visit-table pure-table pure-table-bordered">
			            <tr>
			              <th>Date</th>
			              <th>Teacher</th>
			              <th>Grade</th>
			              <th>Program</th>
			        	</tr>
				        <?php
				            
				        
				      	foreach ($visits as $visit)
				      	{
				        	print "<tr><td>".date('M d, Y', strtotime($visit['event_date']))."</td>";
			                print "<td>".$visit['teacher_name']."</td>";
			                print "<td>".$visit['grade']."</td>";
			                print "<td>".$visit['program_name']."</td></tr>";
				        }
				        ?>
				    </table>
				    
				<?php 
				}
				else
				{?>
					<p style="margin: 15px 25px;text-align: center;">You have no visits currently scheduled to Gould Lake. Hopefully you can come out soon!</p>
				<?php 
				} ?>
				
				<p style="margin: 15px 25px;text-align: center;">
					
				<?php
				if ($school['early'] == "Y") 
				{ print "* Our files show that ".$school['school_name']." begins the school day prior to 9:00AM.  The expected <b>arrival time</b> for early schools is 9:30AM.  If you are going to be earlier or later than this, please let our office know so that we can make arrangments with Gould Lake staff.";}
				else
				{print "* Our files show that ".$school['school_name']." begins the school day after 9:00AM.  The expected <b>arrival time</b> for regular schedule schools is 10:00AM.  If you are going to be earlier or later than this, please let our office know so that we can make arrangments with Gould Lake staff.";}
			?>
			</p>
		</div>
	</div>
  	<?php 
  	}
  	else 
  	{
	?>
	  	<section>
	  		<div id="school-login">
		  		<form id="school-login-form" class="pure-form pure-form-stacked" method="post" action="process-login.php">
		        	<label>Please enter your school code...</label>
		        	<input style="width: 150px;" type="text" name="school_code" />
		          	<button type="submit">Submit</button>
	        	</form>
	        </div>
	    </section>
    <?php 
	}
        
	include ('sch-footer.php');
?>