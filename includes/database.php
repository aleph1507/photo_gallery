<?php 

	require_once("config.php");

	class MySQLDatabase {

		private $connection;

		public function __construct(){
			$this->open_connection();
		}

		public function open_connection(){
			$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
			if(!$this->connection){
				die("Database connection failed: " . mysqli_error($this->connection));
			} else {
				$db_select = mysqli_select_db($this->connection, DB_NAME);
				if(!$db_select)
					die('Database selection failed: ' . mysqli_error($this->connection));
			} 
		}

		public function close_connection(){
			if(isset($this->connection)){
				mysqli_close($this->connection);
				unset($this->connection);
			}
		}

		public function query($sql){
			$result = mysqli_query($this->connection, $sql);
			$this->confirm_query($result);
			return $result;
		}

		private function confirm_query($result){
			if(!$result)
				die('Database query failed: ' . mysqli_error($this->connection));
		}

		public function mysql_prep($value){
			$magic_quotes_active = get_magic_quotes_gpc();
			$new_enough_php = function_exists("mysqli_real_escape_string"); // PHP >= v4.3.0
			if($new_enough_php){
				// echo "<br>new enough php<br>";
				if($magic_quotes_active){
					// $value = stripslashes($value);
					// echo "magic quotes active<br>";
				} else {
					if(!$magic_quotes_active){
						// echo "<br>magic quotes not active<br>";
						$value = addslashes($value);
					}
				}
			}
			// var_dump($value);
			// die();
			return $value;
		}
	}

	$database = new MySQLDatabase();
	$db =& $database;
	
?>