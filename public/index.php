<?php 

	require_once('../includes/initialize.php');

	

	// $sql = "INSERT INTO users (id, username,
	// 			password, first_name, last_name) VALUES (1, 'kskoglund', 'secretpwd', 'Kevin','Skoglund'); ";
	

	$user = User::find_by_id(1);
	
	// echo $user->full_name();
	// echo $record['username'];

	// echo '<hr>';

	$users = User::find_all();
	// foreach($users as $user) {
	// 	echo 'User: ' . $user->username . '<br>';
	// 	echo 'Name: ' . $user->full_name() . '<br><br>';
	// }

	$photos = Photograph::find_all();

?>


<?php include_layout_template('header.php'); ?>

<?php foreach($photos as $photo): ?>
	<div style="float:left; margin-left:20px;">
		<a href="photo.php?id=<?php echo $photo->id; ?>">
			<img src="<?php echo $photo->image_path(); ?>" width="200">
		</a>
		<p><?php echo $photo->caption; ?></p>
	</div>
<?php endforeach; ?>

<?php include_layout_template('footer.php'); ?>