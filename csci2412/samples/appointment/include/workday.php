<?php
namespace Classes;
use \DateTime as DateTime;

class WorkDay {
	const FIRST_WEEK_DAY = 1;
	const LAST_WEEK_DAY = 7;
	const HALF_HOURS = 24 * 2;  // get number of 30min time slots in a day

	public $day;
	public $open;
	public $close;

	function __construct($day, $open, $close) {
		$this->day = $day;
		$this->open = $open;
		$this->close = $close;
	}

	public function getOpenDateTime($now = null) {
		return $this->getNewDateTime($this->open, $now);
	}

	public function getCloseDateTime($now = null) {
		return $this->getNewDateTime($this->close, $now);
	}

	private function getNewDateTime($time, $now) {
		if ($now == null) $now = new DateTime();
		$ts = strtotime($time, $now->getTimestamp());
		$dt = new DateTime();
		return $dt->setTimestamp($ts);
	}

	// function to update currentDayOfWeek during our main loop
	public static function setDayOfWeek(&$currDay) {
		if ($currDay >= self::LAST_WEEK_DAY)
			return $currDay = self::FIRST_WEEK_DAY;
		return $currDay++;
	}
}
?>
