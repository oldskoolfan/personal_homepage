<?php

include "{$_SERVER['DOCUMENT_ROOT']}/include/db-connect.php";

$thoughts = $con->query('select * from thoughts order by date_modified desc');
