<?php 

	require_once(LIB_PATH.DS.'database.php');

	class User extends DatabaseObject {

		static protected $table_name='users';
		protected static $db_fields = array('id', 'username', 'password', 'first_name', 'last_name');

		public $id;
		public $username;
		public $password;
		public $first_name;
		public $last_name;


		public function full_name(){
			if(isset($this->first_name) && isset($this->last_name)) {
				return $this->first_name . ' ' . $this->last_name;
			} else {
				return '';
			}
		}

		public static function authenticate($username="", $password=""){
			global $database;
			$username = $database->escape_value($username);
			$password = $database->escape_value($password);

			$sql = "SELECT * FROM users ";
			$sql .= "WHERE username = '{$username}' ";
			$sql .= "AND password = '{$password}' ";
			$sql .= "LIMIT 1";

			$result_array = self::find_by_sql($sql);
			if(!empty($result_array)){
				Logger::log_action('Login', "{$username} logged in");
			}
			return !empty($result_array) ? array_shift($result_array) : false;
		}

		// protected function attributes() {
		// 	// return an array of attribute keys and values
		// 	// return get_object_vars($this);
		// 	$attributes = array();
		// 	foreach(self::$db_fields as $field){
		// 		if(property_exists($this, $field)) {
		// 			$attributes[$field] = $this->$field;
		// 		}
		// 	}

		// 	return $attributes;
		// }

		// protected function sanitized_attributes() {
		// 	global $database;
		// 	$clean_attributes = array();

		// 	foreach($this->attributes() as $key => $value){
		// 		$clean_attributes[$key] = $database->escape_value($value);
		// 	}
		// 	return $clean_attributes;
		// }

		// Common DB methods:

		// public static function find_all() {
		// 	global $database;
		// 	$result_set = $database->query("SELECT * FROM users");
		// 	return $result_set;
		// }

		// public static function find_all(){
		// 	return self::find_by_sql("SELECT * FROM ".self::$table_name);
		// }

		// public static function find_all(){
		// 	return self::find_by_sql("SELECT * FROM users");
		// }

		// public static function find_by_id($id=0){
		// 	global $database;
		// 	$result_set = $database->query("SELECT * FROM users WHERE id={$id}");
		// 	$found = $database->fetch_array($result_set);
		// 	return $found;
		// }

		// public static function find_by_id($id=0){
		// 	global $database;
		// 	$result_array = self::find_by_sql("SELECT * FROM users WHERE id={$id} LIMIT 1");
		// 	return !empty($result_array) ? array_shift($result_array) : false;
		// }

		// public static function find_by_id($id=0){
		// 	global $database;
		// 	$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id={$id} LIMIT 1");
		// 	return !empty($result_array) ? array_shift($result_array) : false;
		// }

		// public static function find_by_sql($sql=""){
		// 	global $database;
		// 	$result_set = $database->query($sql);
		// 	$object_array = array();
		// 	while($row = $database->fetch_array($result_set)){
		// 		$object_array[] = self::instantiate($row);
		// 	}
		// 	return $object_array;
		// } 

		// private static function instantiate($record){
		// 	// could check record array
		// 	// simple long form apprach
		// 	$object 			= new self();
		// 	// $object->id 		= $record['id'];
		// 	// $object->username 	= $record['username'];
		// 	// $object->password 	= $record['password'];
		// 	// $object->first_name = $record['first_name'];
		// 	// $object->last_name 	= $record['last_name'];

		// 	// more dynamic, short form approach
		// 	foreach($record as $attribute=>$value){
		// 		if($object->has_attribute($attribute))
		// 			$object->$attribute = $value;
		// 	}

		// 	return $object;
		// }

		// private function has_attribute($attribute){
		// 	// get_object vars returns an associative array with all attributes, incl. private ones, as the keys and their current values as the value

		// 	$object_vars = get_object_vars($this);
		// 	// we just need to know weather the key exists, true or false
		// 	return array_key_exists($attribute, $object_vars);
		// }

		// public function save() {
		// 	// a new record won't have an id yet
		// 	return isset($this->id) ? $this->update() : $this->create();
		// }

		// public function create() {
		// 	global $database;

		// 	$sql = "INSERT INTO " . self::$table_name . " ("	;
		// 	$sql .= "username, password, first_name, last_name";
		// 	$sql .= ") VALUES ('";
		// 	$sql .= $database->escape_value($this->username) . "', '";
		// 	$sql .= $database->escape_value($this->password) . "', '";
		// 	$sql .= $database->escape_value($this->first_name) . "', '";
		// 	$sql .= $database->escape_value($this->last_name) . "')";

		// 	if($database->query($sql)){
		// 		$this->id = $database->insert_id($database);
		// 		return true;
		// 	} else {
		// 		return false;
		// 	}
		// }

		// public function update() {
		// 	global $database;

		// 	// UPDATE table SET key='value', key='value' WHERE condition

		// 	$sql = 'UPDATE ' . self::$table_name . ' SET ';
		// 	$sql .= "username='" . $database->escape_value($this->username) . "', ";
		// 	$sql .= "password='" . $database->escape_value($this->password) . "', ";
		// 	$sql .= "first_name='" . $database->escape_value($this->first_name) . "', ";
		// 	$sql .= "last_name='" . $database->escape_value($this->last_name) . "' ";
		// 	$sql .= "WHERE id=" . $database->escape_value($this->id);

		// 	$database->query($sql);

		// 	return ($database->affected_rows() == 1) ? 'true' : 'false';
		// }

		// public function delete(){
		// 	global $database;
		// 	// DELETE FROM table WHERE condition LIMIT 1

		// 	$sql = "DELETE FROM " . self::$table_name;
		// 	$sql .= " WHERE id=" . $database->escape_value($this->id);
		// 	$sql .= " LIMIT 1";

		// 	$database->query($sql);

		// 	return ($database->affected_rows() == 1) ? 'true' : 'false';
		// }
	}

?>