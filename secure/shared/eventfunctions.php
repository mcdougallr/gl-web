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

function getstafflist ($id, $conn)
{
	$stmt = $conn->prepare("SELECT staff_name_last, staff_name_common FROM staff_workdays
												LEFT JOIN staff ON staff.staff_id = staff_workdays.workday_staff_id
	            								WHERE workday_event_id = :workday_event_id");
	$stmt->bindValue(':workday_event_id', $id);
	$stmt->execute();
	$stafflist = $stmt->fetchAll();				
	
	return $stafflist;							
}

function geteventcount ($type, $date, $conn)
{
		$stmt = $conn->prepare("SELECT event_id FROM schedule_events WHERE event_date = :event_date AND event_type = :event_type");
		$stmt->bindValue(':event_date', $date);
		$stmt->bindValue(':event_type', $type);
		$stmt->execute();
		$events = $stmt->fetchAll();
		return $events;
}

function getstaffevents ($type, $staff, $date, $conn)
{
		$stmt = $conn->prepare("SELECT * FROM schedule_events
								LEFT JOIN staff_workdays ON schedule_events.event_id = staff_workdays.workday_event_id
								WHERE event_date = :event_date AND event_type = :event_type AND workday_staff_id = :staff_id");
		$stmt->bindValue(':event_date', $date);
		$stmt->bindValue(':event_type', $type);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->execute();
		$events = $stmt->fetchAll();
		return $events;
}

function getstaffeventscount ($type, $staff, $date, $conn)
{
		$stmt = $conn->prepare("SELECT event_id FROM schedule_events
								LEFT JOIN staff_workdays ON schedule_events.event_id = staff_workdays.workday_event_id
								WHERE event_date = :event_date AND event_type = :event_type AND workday_staff_id = :staff_id");
		$stmt->bindValue(':event_date', $date);
		$stmt->bindValue(':event_type', $type);
		$stmt->bindValue(':staff_id', $staff);
		$stmt->execute();
		$events = $stmt->fetchAll();
		return $events;
}
?>