<?php 

	require_once('../../includes/functions.php');
	require_once('../../includes/session.php');
	if(!$session->is_logged_in())
		redirect_to('login.php');

?>

<!DOCTYPE html>
<html>
<head>
	<title>Photo Gallery</title>
	<link rel="stylesheet" href="../stylesheets/main.css" media="all" type="text/css">
</head>
<body>

	<div id="header">
		<h1>Photo Gallery</h1>
	</div>

	<div id="main">
		<h2>Menu</h2>
	</div>

	<div id="footer">Copyright <?php echo date("Y", time()); ?> asdf</div>
</body>
</html>