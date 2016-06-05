<?php

$home = getenv('HOME');

include "$home/etc/db-connect.php";

$con->select_db('andrewfharrisdb');
