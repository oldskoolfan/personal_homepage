<?php
namespace Classes;
class Appointment {
	public $date;
	public $time;
	public $timestamp;

	function __construct($date, $time, $ts) {
		$this->date = $date;
		$this->time = $time;
		$this->timestamp = $ts;
	}

	public static function doesApptExist($appts, $ts) {
		foreach($appts as $appt) {
			if ($appt->timestamp == $ts) return true;
		}
		return false;
	}

	public static function dayHasAtLeastOneAppt($appts, $day) {
		$date = $day->format('Y-m-d');
		foreach($appts as $appt) {
			if ($appt->date == $date) return true;
		}
		return false;
	}
}
?>
