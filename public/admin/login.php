<?php 

	require_once('../../includes/initialize.php');

	if($session->is_logged_in())
		redirect_to('index.php');

	// form name="submit"
	if(isset($_POST['submit'])){ //form's been submitted
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);

		//check db for user/pass
		$found_user = User::authenticate($username, $password);

		if($found_user) {
			$session->login($found_user);
			redirect_to('index.php');
		} else {
			// user/pass wrong
			$message = 'Username/password combination incorrect.';
		}
	} else { //form not submitted
		$username = '';
		$password = '';
		$message = '';
	}

?>

<!-- <!DOCTYPE html>
<html>
<head>
	<title>Photo Gallery</title>
	<link rel="stylesheet" href="../stylesheets/main.css" media="all" type="text/css">
</head>
<body>
	<div id="header">
		<h1>Photo Gallery</h1>
	</div>
	<div id="main"> -->

		<?php include_layout_template('admin_header.php'); ?>
		<h2>Staff Login</h2>
		<?php echo output_message($message); ?>
		<form action="login.php" method="POST">
			<table>
				<tr>
					<td>Username:</td>
					<td>
						<input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username)?>">
					</td>
				</tr>
				<tr>
					<td>Password:</td>
					<td>
						<input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password) ?>">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" name="submit" value="Login">
					</td>
				</tr>
			</table>
		</form>
	</div>
	<?php include_layout_template('admin_footer.php'); ?>
	<!--  -->