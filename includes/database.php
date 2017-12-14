<?php 

	require_once("config.php");

	class MySQLDatabase {

		private $connection;
		public $last_query;
		private $magic_quotes_active;
		private $real_escape_string_exists;
		public function __construct(){
			$this->open_connection();
			$this->magic_quotes_active = get_magic_quotes_gpc();
			$this->real_escape_string_exists = function_exists("mysqli_real_escape_string"); // PHP >= v4.3.0
		}

		public function open_connection(){
			$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
			if(!$this->connection){
				die("Database connection failed: " . mysqli_connect_error($this->connection));
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
			$this->last_query = $sql;
			$result = mysqli_query($this->connection, $sql);
			$this->confirm_query($result);
			return $result;
		}

		private function confirm_query($result){
			if(!$result){
				$output = "Database query failed: " . mysqli_error($this->connection) . "<br><br>";
				$output .= "Last SQL Query: " . $this->last_query;
				die($output);
			}
		}

		public function escape_value($value){
			
			if($this->real_escape_string_exists){
				// echo "<br>new enough php<br>";
				if($this->magic_quotes_active){
					$value = stripslashes($value);
					// echo "magic quotes active<br>";
				} else {
					if(!$this->magic_quotes_active){
						// echo "<br>magic quotes not active<br>";
						$value = addslashes($value);
					}
				}
			}
			// var_dump($value);
			// die();
			return $value;
		}

		public function fetch_array($result_set){
			return mysqli_fetch_array($result_set);
		}

		public function num_rows($result_set){
			return mysqli_num_rows($result_set);
		}

		public function insert_id(){
			// get last id inserted in mysql db
			return mysqli_insert_id();
		}

		public function affected_rows(){
			return mysqli_affected_rows($this->connection);
		}

	}

	$database = new MySQLDatabase();
	$db =& $database;
	
?>