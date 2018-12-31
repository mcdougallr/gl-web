<?php
function getevents ($type, $date, $conn)
{
		$stmt = $conn->prepare("SELECT * FROM schedule_events WHERE event_date = :event_date AND event_type = :event_type");
		$stmt->bindValue(':event_date', $date);
		$stmt->bindValue(':event_type', $type);
		$stmt->execute();
		$events = $stmt->fetchAll();
		return $events;
}
	
function getworkdaysall ($type, $date, $conn)
{
	if ($type == "V"){
		$stmt = $conn->prepare("SELECT * FROM fws_visits 
		              								LEFT JOIN fws_schools ON fws_schools.school_id = fws_visits.school_id
		              								LEFT JOIN fws_programs ON fws_programs.program_id = fws_visits.program_id
		              								WHERE visit_date= :visit_date");
		$stmt->bindValue(':visit_date', $date);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else if ($type == "T"){
		$stmt = $conn->prepare("SELECT * FROM fws_tasks 
		              								LEFT JOIN fws_programs ON fws_programs.program_id = fws_tasks.program_id
		              								WHERE task_date= :task_date");
		$stmt->bindValue(':task_date', $date);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else if ($type == "E"){
		$stmt = $conn->prepare("SELECT * FROM log_eq_days WHERE eq_day_date = :eq_day_date");
		$stmt->bindValue(':eq_day_date', $date);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else if ($type == "F"){
		$stmt = $conn->prepare("SELECT * FROM log_food_days WHERE food_day_date = :food_day_date");
		$stmt->bindValue(':food_day_date', $date);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else if ($type == "D"){
		$stmt = $conn->prepare("SELECT * FROM log_drive_days WHERE drive_day_date= :drive_day_date");
		$stmt->bindValue(':drive_day_date', $date);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else if ($type == "S"){
		$stmt = $conn->prepare("SELECT * FROM log_supervision_days WHERE supervision_day_date= :supervision_day_date");
		$stmt->bindValue(':supervision_day_date', $date);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else if ($type == "B"){
		$stmt = $conn->prepare("SELECT * FROM log_bus_runs 
													LEFT JOIN log_routes ON log_routes.route_id = log_bus_runs.bus_run_route
													WHERE bus_run_date= :bus_run_date");
		$stmt->bindValue(':bus_run_date', $date);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else if ($type == "O"){
		$stmt = $conn->prepare("SELECT * FROM log_office_days WHERE office_day_date = :office_day_date");
		$stmt->bindValue(':office_day_date', $date);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else if ($type == "I"){
		$stmt = $conn->prepare("SELECT * FROM fws_info WHERE info_date= :info_date");
		$stmt->bindValue(':info_date', $date);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else {
		return false;
	}
	return $workdays;
}

function getworkdays ($type, $staff, $date, $conn)
{
	if ($type == "X"){
      	$stmt = $conn->prepare("SELECT gl_staff_workdays.*, gl_staff.admin_summer_confirmed, gl_staff_sessions.*, gl_staff_session_days.* FROM gl_staff_workdays 
												LEFT JOIN gl_staff ON gl_staff.staff_id = gl_staff_workdays.workday_staff_id
		          								LEFT JOIN gl_staff_sessions ON gl_staff_workdays.workday_session = gl_staff_sessions.staffing_session_id
		          								LEFT JOIN gl_staff_session_days ON gl_staff_workdays.workday_sd_id = gl_staff_session_days.sd_id
		          								WHERE workday_date= :workday_date AND workday_staff_id = :staff_id AND admin_summer_confirmed = '1'");
      	$stmt->bindValue(':workday_date', $date);
	   	$stmt->bindValue(':staff_id', $staff);
		$stmt->execute();
	   	$workdays = $stmt->fetchAll();
	}	
	else if ($type == "V"){
		$stmt = $conn->prepare("SELECT * FROM gl_workdays 
		              								LEFT JOIN fws_visits ON fws_visits.visit_id = gl_workdays.workday_day_id
		              								LEFT JOIN fws_schools ON fws_schools.school_id = fws_visits.school_id
		              								LEFT JOIN fws_programs ON fws_programs.program_id = fws_visits.program_id
		              								WHERE visit_date= :visit_date AND workday_staff_id = :staff_id AND workday_type = :workday_type");
		$stmt->bindValue(':visit_date', $date);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->bindValue(':workday_type', $type);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else if ($type == "T"){
		$stmt = $conn->prepare("SELECT * FROM gl_workdays 
		              								LEFT JOIN fws_tasks ON fws_tasks.task_id = gl_workdays.workday_day_id
		              								LEFT JOIN fws_programs ON fws_programs.program_id = fws_tasks.program_id
		              								WHERE task_date= :task_date AND workday_staff_id = :staff_id AND workday_type = :workday_type");
		$stmt->bindValue(':task_date', $date);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->bindValue(':workday_type', $type);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else if ($type == "E"){
		$stmt = $conn->prepare("SELECT * FROM gl_workdays 
													LEFT JOIN log_eq_days ON log_eq_days.eq_day_id = gl_workdays.workday_day_id
													WHERE eq_day_date = :eq_day_date AND workday_staff_id = :staff_id AND workday_type = :workday_type");
		$stmt->bindValue(':eq_day_date', $date);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->bindValue(':workday_type', $type);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else if ($type == "F"){
		$stmt = $conn->prepare("SELECT * FROM gl_workdays 
													LEFT JOIN log_food_days ON log_food_days.food_day_id = gl_workdays.workday_day_id
													WHERE food_day_date = :food_day_date AND workday_staff_id = :staff_id AND workday_type = :workday_type");
		$stmt->bindValue(':food_day_date', $date);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->bindValue(':workday_type', $type);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else if ($type == "D"){
		$stmt = $conn->prepare("SELECT * FROM gl_workdays 
													LEFT JOIN log_drive_days ON log_drive_days.drive_day_id = gl_workdays.workday_day_id
													WHERE drive_day_date= :drive_day_date  AND workday_staff_id = :staff_id AND workday_type = :workday_type");
		$stmt->bindValue(':drive_day_date', $date);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->bindValue(':workday_type', $type);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else if ($type == "S"){
		$stmt = $conn->prepare("SELECT * FROM gl_workdays 
													LEFT JOIN log_supervision_days ON log_supervision_days.supervision_day_id = gl_workdays.workday_day_id
													WHERE supervision_day_date= :supervision_day_date AND workday_staff_id = :staff_id AND workday_type = :workday_type");
		$stmt->bindValue(':supervision_day_date', $date);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->bindValue(':workday_type', $type);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else if ($type == "O"){
		$stmt = $conn->prepare("SELECT * FROM gl_workdays 
													LEFT JOIN log_office_days ON log_office_days.office_day_id = gl_workdays.workday_day_id
													WHERE office_day_date = :office_day_date AND workday_staff_id = :staff_id AND workday_type = :workday_type");
		$stmt->bindValue(':office_day_date', $date);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->bindValue(':workday_type', $type);
		$stmt->execute();
		$workdays = $stmt->fetchAll();
	}
	else {
		return false;
	}
	return $workdays;
}

function getstafflist ($type, $id, $conn)
{
	$stmt = $conn->prepare("SELECT staff_name_last, staff_name_common FROM gl_workdays
												LEFT JOIN gl_staff ON gl_staff.staff_id = gl_workdays.workday_staff_id
	            								WHERE workday_day_id = :workday_day_id AND workday_type = :workday_type");
	$stmt->bindValue(':workday_day_id', $id);
	$stmt->bindValue(':workday_type', $type);
	$stmt->execute();
	$stafflist = $stmt->fetchAll();				
	
	return $stafflist;							
}

function workdaycountstaff ($type, $staff, $date, $conn)
{
	if ($type == "X"){
      	$stmt = $conn->prepare("SELECT workday_id FROM gl_staff_workdays
		          								WHERE workday_date= :workday_date AND workday_staff_id = :staff_id AND admin_summer_confirmed = '1'");
      	$stmt->bindValue(':workday_date', $date);
	   	$stmt->bindValue(':staff_id', $staff);
		$stmt->execute();
	   	$workdaycount = $stmt->fetchAll();
	}	
	else if ($type == "V"){
		$stmt = $conn->prepare("SELECT workday_id FROM gl_workdays 
		              								LEFT JOIN fws_visits ON fws_visits.visit_id = gl_workdays.workday_day_id
		              								WHERE visit_date= :visit_date AND workday_staff_id = :staff_id AND workday_type = :workday_type");
		$stmt->bindValue(':visit_date', $date);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->bindValue(':workday_type', $type);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else if ($type == "T"){
		$stmt = $conn->prepare("SELECT workday_id FROM gl_workdays 
		              								LEFT JOIN fws_tasks ON fws_tasks.task_id = gl_workdays.workday_day_id
		              								LEFT JOIN fws_programs ON fws_programs.program_id = fws_tasks.program_id
		              								WHERE task_date= :task_date AND workday_staff_id = :staff_id AND workday_type = :workday_type");
		$stmt->bindValue(':task_date', $date);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->bindValue(':workday_type', $type);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else if ($type == "E"){
		$stmt = $conn->prepare("SELECT workday_id FROM gl_workdays 
													LEFT JOIN log_eq_days ON log_eq_days.eq_day_id = gl_workdays.workday_day_id
													WHERE eq_day_date = :eq_day_date AND workday_staff_id = :staff_id AND workday_type = :workday_type");
		$stmt->bindValue(':eq_day_date', $date);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->bindValue(':workday_type', $type);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else if ($type == "F"){
		$stmt = $conn->prepare("SELECT workday_id FROM gl_workdays 
													LEFT JOIN log_food_days ON log_food_days.food_day_id = gl_workdays.workday_day_id
													WHERE food_day_date = :food_day_date AND workday_staff_id = :staff_id AND workday_type = :workday_type");
		$stmt->bindValue(':food_day_date', $date);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->bindValue(':workday_type', $type);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else if ($type == "D"){
		$stmt = $conn->prepare("SELECT workday_id FROM gl_workdays 
													LEFT JOIN log_drive_days ON log_drive_days.drive_day_id = gl_workdays.workday_day_id
													WHERE drive_day_date= :drive_day_date  AND workday_staff_id = :staff_id AND workday_type = :workday_type");
		$stmt->bindValue(':drive_day_date', $date);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->bindValue(':workday_type', $type);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else if ($type == "D"){
		$stmt = $conn->prepare("SELECT workday_id FROM gl_workdays 
													LEFT JOIN log_supervision_days ON log_supervision_days.supervision_day_id = gl_workdays.workday_day_id
													WHERE supervision_day_date= :supervision_day_date AND workday_staff_id = :staff_id AND workday_type = :workday_type");
		$stmt->bindValue(':supervision_day_date', $date);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->bindValue(':workday_type', $type);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else if ($type == "O"){
		$stmt = $conn->prepare("SELECT workday_id FROM gl_workdays 
													LEFT JOIN log_office_days ON log_office_days.office_day_id = gl_workdays.workday_day_id
													WHERE office_day_date = :office_day_date AND workday_staff_id = :staff_id AND workday_type = :workday_type");
		$stmt->bindValue(':office_day_date', $date);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->bindValue(':workday_type', $type);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else {
		return false;
	}
	return $workdaycount;							
}

function workdaycount ($type, $date, $conn)
{
	if ($type == "I"){
		$stmt = $conn->prepare("SELECT info_id FROM fws_info WHERE info_date= :info_date");
		$stmt->bindValue(':info_date', $date);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else if ($type == "V"){
		$stmt = $conn->prepare("SELECT visit_id FROM fws_visits WHERE visit_date= :visit_date");
		$stmt->bindValue(':visit_date', $date);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else if ($type == "T"){
		$stmt = $conn->prepare("SELECT task_id FROM fws_tasks WHERE task_date= :task_date");
		$stmt->bindValue(':task_date', $date);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else if ($type == "B"){
		$stmt = $conn->prepare("SELECT bus_run_id FROM log_bus_runs WHERE bus_run_date= :bus_run_date");
		$stmt->bindValue(':bus_run_date', $date);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else if ($type == "E"){
		$stmt = $conn->prepare("SELECT eq_day_id FROM log_eq_days WHERE eq_day_date= :eq_day_date");
		$stmt->bindValue(':eq_day_date', $date);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else if ($type == "F"){
		$stmt = $conn->prepare("SELECT food_day_id FROM log_food_days WHERE food_day_date= :food_day_date");
		$stmt->bindValue(':food_day_date', $date);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else if ($type == "D"){
		$stmt = $conn->prepare("SELECT drive_day_id FROM log_drive_days WHERE drive_day_date= :drive_day_date");
		$stmt->bindValue(':drive_day_date', $date);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else if ($type == "S"){
		$stmt = $conn->prepare("SELECT supervision_day_id FROM log_supervision_days WHERE supervision_day_date= :supervision_day_date");
		$stmt->bindValue(':supervision_day_date', $date);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else if ($type == "O"){
		$stmt = $conn->prepare("SELECT office_day_id FROM log_office_days WHERE office_day_date= :office_day_date");
		$stmt->bindValue(':office_day_date', $date);
		$stmt->execute();
		$workdaycount = $stmt->fetchAll();
	}
	else {
		return false;
	}
	return $workdaycount;							
}

?>