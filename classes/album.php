<?php

class Album {
	public $id;
	public $title;
	public $description;
	public $credits;
	public $releaseYear;
	public $label;

	function __construct($id, $title, $desc, $credits, $year, $label) {
		$this->id = $id;
		$this->title = $title;
		$this->description = $desc;
		$this->credits = $credits;
		$this->releaseYear = $year;
		$this->label = $label;
	}
}
