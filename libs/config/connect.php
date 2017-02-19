<?php
require_once ("config.php");
try {
	$mysql = 'mysql:host=' . HOST . ';dbname=' . DATATABLE;
	$pdo = new PDO($mysql, USERNAME, PASSWORD);
	//POD MYSQL CONNECT CONFIG
} catch(PDOException $e) {
	echo $e -> getMessage();
	//ERROR RETURN
}
?>