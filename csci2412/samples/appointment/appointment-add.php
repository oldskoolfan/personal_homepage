<?php
// db connect
include "/home/andrew/etc/db-connect.php";
$con->select_db('appointmentdb');

$ts = isset($_GET['appt']) ? $_GET['appt'] : null;
$page = isset($_GET['page']) ? $_GET['page'] : 0;

if ($ts != null) {
	// see if an appointment already exists for this time
	$result = $con->query("select * from appointments where appointment_timestamp = $ts");
	if ($result->num_rows == 0) {
		$query = "insert into appointments (appointment_date, appointment_time, appointment_timestamp)
			values (date(from_unixtime($ts)),time(from_unixtime($ts)),$ts)";

		echo $query;
		$result = $con->query($query);
		if (!$result) {
			echo "<h3>Error: $con->error</h3>";
			echo '<a href="./">Back to Calendar</a>';
			return;
		}
	}
}
header("Location: ./?page=$page");
?>
