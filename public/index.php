<?php 

	require_once('../includes/initialize.php');

	

	// $sql = "INSERT INTO users (id, username,
	// 			password, first_name, last_name) VALUES (1, 'kskoglund', 'secretpwd', 'Kevin','Skoglund'); ";
	

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