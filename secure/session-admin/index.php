<?php 
	$page_title = "GL Session Admin";
	include ("ses-header.php"); 
?>

<link rel="stylesheet" href="_index.css">

<div id="registration_overview" class="pure-g">
	<div id="session-table" class="pure-u-1">
		<h1>Session Overview</h1>
		<table class="pure-table pure-table-bordered">
			<tr>
				<th style="text-align: left">Session Name</th>
				<th class="hidesmall">Start</th>
				<th class="hidesmall">End</th>
		        <th class="hidesmall">Capacity</th>
		        <th>Sections</th>
		        <th colspan= 2>Trips</th>
		        <th>Debriefs</th>
    		</tr>

			<?php    
			$sessionquery = "SELECT * FROM ss_sessions ORDER BY session_sortorder";
			foreach ($conn->query($sessionquery) as $session)
			{
				$stmt = $conn->prepare("SELECT section_id FROM ss_session_sections WHERE section_session_id = :section_session_id");
				$stmt->bindValue(':section_session_id', $session['session_id']);
				$stmt->execute();				
				$sections = $stmt->fetchAll();
				$scount = count($sections);
					
				$placements = 0; ?>
				<tr>
					<td style="text-align: left" rowspan=<?php print $scount; ?>>
						<?php
						if ($staffaccess['staff_access'] > 3)
						{print "<a href=\"ses-session-edit.php?sid=".$session['session_id']."\" class=\"plaintext-button\">";}
						print $session['session_program_code'].$session['session_number']; 
						if ($staffaccess['staff_access'] > 3)
						{print "</a>";}
						?>
					</td>
					<td rowspan=<?php print $scount; ?> class="hidesmall"><?php print $session['session_start']; ?></td>
					<td rowspan=<?php print $scount; ?> class="hidesmall"><?php print $session['session_end']; ?></td>
					<td rowspan=<?php print $scount; ?> class="hidesmall"><?php print $session['session_capacity']; ?></td>
					<?php 
						$stmt = $conn->prepare("SELECT section_id, section_name FROM ss_session_sections WHERE section_session_id = :section_session_id ORDER BY section_sortorder");
						$stmt->bindValue(':section_session_id', $session['session_id']);
						$stmt->execute();				
						$sections = $stmt->fetchAll();
						$first = 1;
						foreach ($sections as $section)
						{
							if ($first == 1) {$first = 0;}
							else {print "<tr>";}?>
							<td>
								<?php
								if ($staffaccess['staff_access'] > 3)
								{print "<a href=\"ses-section-edit.php?sid=".$section['section_id']."\" class=\"plaintext-button\">";}
								print $section['section_name'];
								if ($staffaccess['staff_access'] > 3)
								{print "</a>";}
								?>
							</td>
							<td><a href="ses-trips.php?sid=<?php print $section['section_id']; ?>" class="plaintext-button"><i class="fa fa-pencil"></i></a></td>
							<td><a href="reports/r-floatplan.php?sid=<?php print $section['section_id']; ?>" class="plaintext-button" target="_blank"><i class="fa fa-print"></i></a></td>
							<td><a href="ses-debrief.php?sid=<?php print $section['section_id']; ?>" class="plaintext-button"><i class="fa fa-pencil"></i></a></td>
							</tr>
							<?php
						}
				}
			?>
		</table>
	</div>
</div>
<?php 
	include("ses-footer.php"); 
?>