<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "stocks";

try {
	$db = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
}catch(PDOException $e) {
	echo $e->getMessage();
}




?>