<?php
$config = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/config.ini");
$etcPath = $config['etc_directory'];
include "$etcPath/db-connect.php";

$con->select_db('andrewfharrisdb');
