<?php

include "{$_SERVER['DOCUMENT_ROOT']}/include/db-connect.php";

$entity = $_GET['entity'];
$id = $_GET['id'];

$result = $con->query("delete from {$entity}s where {$entity}_id = $id");

if (!$result) {
	echo 'Error: problem deleting record. <br><a href="/admin/">Back to admin</a>';
} else {
	header('Location: /admin/' . $entity . 's.php');
}
