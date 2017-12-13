<?php 

	require_once('../includes/database.php');
	echo isset($database) ? 'true' : 'false';
	echo '<br>';
	echo $database->mysql_prep("It's working?<br>");

	$sql = "INSERT INTO users (id, username,
				password, first_name, last_name) VALUES (1, 'kskoglund', 'secretpwd', 'Kevin','Skoglund'); ";
	
	$result = $database->query($sql);

	$sql = "SELECT * FROM users WHERE id = 1";
	$result_set = $database->query($sql);
	$found_user = mysqli_fetch_array($result_set);
	echo $found_user['username'];
?>