<?php
// Content of database.php
 
$mysqli = new mysqli('localhost', 'news_user', 'news_pass', 'news_site');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>