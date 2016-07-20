<?php

include "{$_SERVER['DOCUMENT_ROOT']}/include/db-connect.php";
$result = $con->query(
	'select a.*,
	s.song_id,
	s.song_title,
	s.recorded_date,
	s.file_type,
	s.credits
from albums as a join songs as s using(album_id)');
$albums = [];
$currentAlbumId = null;
$album;
foreach($result as $row) {
	if ($currentAlbumId !== $row['album_id']) {
		// need a new album
		if (isset($album)) {
			array_push($albums, $album);
		}
		$currentAlbumId = $row['album_id'];
		$album = [
			"album_id" => $row['album_id'],
			"album_title" => $row['album_title'],
			"release_year" => $row['release_year'],
			"album_desc" => $row['album_desc'],
			"credits" => $row['credits'],
			"label" => $row['label'],
			"songs" => []
		];
	}
	array_push($album['songs'], [
		"song_id" => $row['song_id'],
		"song_title" => $row['song_title'],
		"recorded_date" => $row['recorded_date'],
		//"audio" => 'longblob',
		"file_type" => $row['file_type'],
		"credits" => $row['credits']
	]);
}
if (isset($album)) { // last one
	array_push($albums, $album);
}
