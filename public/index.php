<?php 

	require_once('../includes/functions.php');
	require_once('../includes/database.php');
	require_once('../includes/user.php');

	// echo isset($database) ? 'true' : 'false';
	// echo '<br>';
	// echo $database->escape_value("It's working?<br>");

	// $sql = "INSERT INTO users (id, username,
	// 			password, first_name, last_name) VALUES (1, 'kskoglund', 'secretpwd', 'Kevin','Skoglund'); ";
	
	// $result = $database->query($sql);

	// $sql = "SELECT * FROM users WHERE id = 1";
	// $result_set = $database->query($sql);
	// $found_user = $database->fetch_array($result_set);
	// echo $found_user['username'];

	// echo '<hr>';

	$user = User::find_by_id(1);
	
	echo $user->full_name();
	// echo $record['username'];

	echo '<hr>';

	$users = User::find_all();
	foreach($users as $user) {
		echo 'User: ' . $user->username . '<br>';
		echo 'Name: ' . $user->full_name() . '<br><br>';
	}

?>