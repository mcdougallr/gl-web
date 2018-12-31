<?php 
	$page_title = "GL Session List";
	include ("sta-header.php"); 

	$session_id = cleantext($_GET['sid']);
	
	$stmt = $conn->prepare("SELECT * FROM ss_sessions 
													LEFT JOIN ss_programs ON ss_sessions.session_program_code = ss_programs.program_code 
													WHERE session_id = :session_id");
  	$stmt->bindValue(':session_id', $session_id);
  	$stmt->execute();
	$session = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<link rel="stylesheet" href="_sta_session_list.css">

<!-- Start Main Window -->
<div id="session-table">
	<h1 style="text-align: center;">
		Session List - <?php print $session['session_program_code'] ." ". $session['session_number']." - Capacity: ".$session['session_capacity']; ?>
		<i style="margin-left: 10px" id="print-session-list-button" class="fa fa-print"></i>
	</h1>
	<table class="pure-table pure-table-bordered">
		<tr>
			<th width=10%>#</th>
			<th width=60% style="text-align: left;">Student Name</th>
			<th width=30%>Gender</th>
		</tr>				
		<?php
			$count=1;
			$stmt = $conn->prepare("SELECT registration_id, student_name_last, student_name_first, student_sex, admin_mf, admin_if, admin_df, admin_olp FROM ss_registrations 
															LEFT JOIN ss_programs ON ss_registrations.selected_program = ss_programs.program_id
															WHERE accepted_session = :accepted_session 
															ORDER BY selected_program, student_name_last, student_name_first");
			$stmt->bindValue(':accepted_session', $session_id);
			$stmt->execute();
			$result = $stmt->fetchAll();
			
			foreach ($result as $students)
	    {?>					
				<tr>
					<td align=center><?php print $count; ?></td>
					<td style="text-align: left;">
						<a href="../student-edit/index.php?sid=<?php print $students['registration_id']; ?>#profile"><?php print $students['student_name_last'] .", ". $students['student_name_first']." "; ?></a>
							<?php 
								if ($students['admin_olp']==1)
									{print "*";}
								if ($students['admin_mf']==1)
									{print "<i style=\"color: #C03030;\" class=\"fa fa-plus\"></i>";}
								if ($students['admin_df']==1)
									{print "<i style=\"color: #F0F090;\" class=\"fa fa-plus\"></i>";}
								if ($students['admin_if']==1)
									{print "<i style=\"color: #6CBDFF;\" class=\"fa fa-plus\"></i>";}
							?>
					</td>
					<td align=center><?php print $students['student_sex']; ?></td>
				</tr>
				<?php $count++;
			}
		?>
	</table>
</div>

<?php include("sta-footer.php"); ?>

<script>

$(document).ready(function(){
	
	$("#print-session-list-button").click(function() {				    
		window.open("reports/s-course-list.php?s="+<?php print $session_id; ?>,'_blank');
	});

});

</script>