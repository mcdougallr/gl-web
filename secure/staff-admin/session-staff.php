<h1>Session Staff</h1>

<div id="staff-list-container">
  	<?php
  	$i = 0;
	$sectionquery = "SELECT * FROM ss_session_sections 
									LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_session_sections.section_session_id
									ORDER BY session_sortorder, section_sortorder";
	
	foreach ($conn->query($sectionquery) as $section)
    {
		$selected_section = $section['section_id'];?>
		<div class="staff-list">
			<div class="session-name">
				<?php 
					$first = 0;
					$stmt = $conn->prepare("SELECT staff_id, staff_sex, staff_name_last, staff_name_common, staff_email, admin_summer_display, 										admin_summer_confirmed, cert_swim_num, cert_FA, admin_stafftype, staff_access
																	FROM staff_workdays
																	LEFT JOIN schedule_events ON schedule_events.event_id = staff_workdays.workday_event_id
																	LEFT JOIN schedule_summer ON schedule_summer.summer_id = schedule_events.event_type_id
																	LEFT JOIN staff ON staff_workdays.workday_staff_id = staff.staff_id 
																	WHERE summer_section_id = '$selected_section' AND event_type = 'X'
																	GROUP BY staff_id
																	ORDER BY staff_sex, staff_name_last, staff_name_first");
					$stmt->execute();				
					$sessionstaffers = $stmt->fetchAll();
					print $section['session_program_code'] . $section['session_number'] . " - ".$section['section_name']; 
					if ($staffaccess['staff_access'] > 1) 
					{
						print "<a href=\"mailto:";
						foreach ($sessionstaffers as $sessionstaff)
						{
							if ($first != 0) {print ";";}
							else {$first = 1;}
							print $sessionstaff['staff_email'];
						}
						print "\"><i class=\"fa fa-envelope\" style=\"color: #222; margin-left: 10px;\"></i></a>";
				} ?>
			</div>
			<div class="session-name-staff">
				<div class="pure-g">
					<div class="pure-u-1-2" style="text-align: left;">
						<?php
						$newsex = "";
						$oldsex = "";
						$first = 0;
						foreach ($sessionstaffers as $sessionstaff)
						{
							$newsex = $sessionstaff['staff_sex'];
							if ($newsex != $oldsex and $first != 0) {print "</div><div class=\"pure-u-1-2\" style=\"text-align: left;\">";}	
							$first = 1;
							print "<div style=\"font-size: .8em;	color: #222;\">";
							if ($_SESSION['refer'] == "sa")
							{
								print "<a href=\"../staff-edit/index.php?sid=".$sessionstaff['staff_id']."\">";
								if ($sessionstaff['admin_summer_display'] == 1) {print "<i style=\"font-size: .8em;color: #0C6;\" class=\"fa fa-eye\"></i>";}
								else
								{
									if ($sessionstaff['admin_summer_confirmed'] == 1) {print "<i style=\"font-size: .8em;color: #F00;\" class=\"fa fa-check\"></i>";}
									else {print "<i style=\"font-size: .8em;color: #222;\" class=\"fa fa-clock-o\"></i>";}
								}
								print " ".$sessionstaff['staff_name_common'] . " " .$sessionstaff['staff_name_last'][0];
								if (strpos($sessionstaff['cert_swim_num'], "NLS") !== false) {print " <i style=\"font-size: .8em;color:#039;\" class=\"fa fa-life-ring\"></i>";}
								if (strpos($sessionstaff['cert_FA'], "WFR") !== false) {print " <i style=\"font-size: .8em;color:#F00;\" class=\"fa fa-plus\"></i>";}
								if (strpos($sessionstaff['admin_stafftype'], "BEd") !== false) {print " <i style=\"font-size: .8em;color: #222;\" class=\"fa fa-graduation-cap\"></i>";}
								if ($staffaccess['staff_access'] > 3) {print "</a>";}
							}
							elseif ($sessionstaff['admin_summer_display'] == 1) 
							{
								print "<i style=\"font-size: .8em;color: #0C6;margin-right: 5px;\" class=\"fa fa-check\"></i>".$sessionstaff['staff_name_common'] . " " .$sessionstaff['staff_name_last'][0];
								if (strpos($sessionstaff['cert_swim_num'], "NLS") !== false) {print " <i style=\"font-size: .8em;color:#039;\" class=\"fa fa-life-ring\"></i>";}
								if (strpos($sessionstaff['cert_FA'], "WFR") !== false) {print " <i style=\"font-size: .8em;color:#F00;\" class=\"fa fa-plus\"></i>";}
								if (strpos($sessionstaff['admin_stafftype'], "BEd") !== false) {print " <i style=\"font-size: .8em;color: #222;\" class=\"fa fa-graduation-cap\"></i>";}
							}
							print "</div>";
							$oldsex = $newsex;
						}
						?>
					</div>
				</div>
    		</div>
  		</div>
  		<?php
    	if ($i == 0){$i = 1;} else {$i = 0;}
    }
  	?>
</div>