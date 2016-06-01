<?php
include "/home/andrew/etc/db-connect.php";
$con->select_db('appointmentdb');

$ts = isset($_GET['appt']) ? $_GET['appt'] : null;
$page = isset($_GET['page']) ? $_GET['page'] : 0;

if ($ts != null) {
	$result = $con->query("delete from appointments where appointment_timestamp = $ts");
	if (!$result) {
		echo "<h3>Error: $con->error</h3>";
		echo '<a href="./">Back to Calendar</a>';
		return;
	}
}

header("Location: ./?page=$page");
?>
