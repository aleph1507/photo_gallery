<?php 

	require_once('../../includes/initialize.php');

	if(!$session->is_logged_in())
		redirect_to('login.php');

	if(isset($_GET['submit'])){
		Logger::clear_log();
	}

?>


<?php include_layout_template('admin_header.php'); ?>
		<h2>Logfile</h2>

		<p>
			<?php 
				echo Logger::read_log();
			?>
		</p>

		<p>
			<form action="logfile.php" method="GET">
				<input type="submit" name="submit" value="Clear Log File">
			</form>
		</p>

		</div>

<?php include_layout_template('admin_footer.php'); ?>