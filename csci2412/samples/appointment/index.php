<!doctype html>
<html>
<head>
<title>Appointment Calendar Example</title>
<link rel="stylesheet" href="include/styles.css">
</head>
<body>
<?php
	include "include/workday.php";
	include "include/appointment.php";
	use Classes\Workday as Workday,
		Classes\Appointment as Appointment;

	// db connect
	//$con = new mysqli('localhost','root','','appointmentdb');
	include "/home/andrew/etc/db-connect.php";
	$con->select_db('appointmentdb');

	// get hours of operation from database
	$result = $con->query('select day_of_week+0 as day_of_week,
		open_time, close_time from operating_hours');

	// create an array of objects from database result
	$workDays = [];
	if ($result) {
		while ($workDay = $result->fetch_object()) {
			array_push(
				$workDays,
				new WorkDay(
					$workDay->day_of_week,
					$workDay->open_time,
					$workDay->close_time
				)
			);
		}
	}

	// get appointments from database
	$result = $con->query('select * from appointments order by appointment_timestamp');

	// create an array of objects from database result
	$appts = [];
	if ($result) {
		while ($appt = $result->fetch_object()) {
			array_push(
				$appts,
				new Appointment(
					$appt->appointment_date,
					$appt->appointment_time,
					$appt->appointment_timestamp
				)
			);
		}
	}

	// get page parameter if there is one
	$page = isset($_GET['page']) ? $_GET['page'] : 0;
	$prev = $page - 1;

	$month = new DateTime();
	$month = $month->modify("first day of $page month");
	$days = $month->format('t'); // get total days in month

	// get our timestamp to get first day of month
	$m = $month->format('m');
	$y = $month->format('Y');
	$ts = mktime(null, null, null, $m, 1, $y);

	// init currentDayOfWeek variable with first day of month
	// note: we add one here because mysql enum is starts on 1, but wday starts on 0
	$currentDayOfWeek = getdate($ts)['wday'] + 1;
?>

<h1>Appointment Calendar: <?=$month->format('F Y')?></h1>
<h4>Click a timeslot to create an appointment, or click an existing appointment to delete it</h4>
<div class="page-links">
	<a href="./?page=<?=$prev?>">Prev</a>|<a href="./?page=<?=$page + 1?>">Next</a>
</div>
<section>
<?php if ($currentDayOfWeek > WorkDay::FIRST_WEEK_DAY): ?>
	<div class="row">
	<!-- echo empty table cells for each day offset from sunday at beginning of month -->
	<?php for ($i = WorkDay::FIRST_WEEK_DAY; $i < $currentDayOfWeek; $i++):?>
		<div class="cell"></div>
	<?php endfor; ?>
<?php endif; ?>
<!-- main loop to go through all days in a month -->
<?php
	for ($i = 1; $i <= $days; $i++) {
		// create new datetime object for the day
		$day = new DateTime("$m/$i/$y");
		// set time to 12am
		$day->setTime(0,0);

		$dayHasAtLeastOneAppt = Appointment::dayHasAtLeastOneAppt($appts, $day);
		$cellClass = $dayHasAtLeastOneAppt ? 'cell has-appt' : 'cell';

		if ($currentDayOfWeek == WorkDay::FIRST_WEEK_DAY) echo '<div class="row">';
		echo "<div class=\"$cellClass\">";
		echo "<div class=\"day-of-month\">{$day->format('j')}</div>";
		echo '<div class="timeslot-wrapper">';
		for ($j = 0; $j < WorkDay::HALF_HOURS; $j++) {
			foreach ($workDays as $workDay) {
				if ($workDay->day == $day->format('w') + 1) {
					if ($workDay->getOpenDateTime($day) <= $day &&
						$workDay->getCloseDateTime($day) >= $day) {
						$apptExists = Appointment::doesApptExist($appts, $day->getTimestamp());
						$action = $apptExists ?	"remove" : "add";
						echo "<a href=\"./appointment-$action.php?appt={$day->getTimestamp()}&page=$page\">
							<li class=\"appt-$action\">{$day->format('h:i a')}</li></a>";
					}
					break;
				}
			}
			$day->modify("+30 minute");
		}
		echo "</div></div>";
		if ($currentDayOfWeek == WorkDay::LAST_WEEK_DAY) echo "</div>";

		WorkDay::setDayOfWeek($currentDayOfWeek);
	}
?>

<?php if ($currentDayOfWeek > WorkDay::FIRST_WEEK_DAY &&
	$currentDayOfWeek < WorkDay::LAST_WEEK_DAY):?>
	<!-- echo empty table cells for each day offset from sunday at end of month -->
	<?php for ($i = $currentDayOfWeek; $i <= WorkDay::LAST_WEEK_DAY; $i++):?>
		<div class="cell"></div>
	<?php endfor; ?>
	</div>
<?php endif; ?>
</section>
<div class="page-links">
	<a href="./?page=<?=$prev?>">Prev</a>|<a href="./?page=<?=++$page?>">Next</a>
</div>
</body>
</html>
