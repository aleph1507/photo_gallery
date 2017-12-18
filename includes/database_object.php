<?php 

	require_once(LIB_PATH.DS.'database.php');

	class DatabaseObject {
		public static function find_all(){
			return self::find_by_sql("SELECT * FROM ".static::$table_name);
		}

		public static function find_by_id($id=0){
			global $database;

			$result_array = self::find_by_sql("SELECT * FROM ".static::$table_name." WHERE id={$database->escape_value($id)} LIMIT 1");
			return !empty($result_array) ? array_shift($result_array) : false;
		}

		public static function find_by_sql($sql=""){
			global $database;
			$result_set = $database->query($sql);
			$object_array = array();
			while($row = $database->fetch_array($result_set)){
				$object_array[] = self::instantiate($row);
			}
			return $object_array;
		} 

		private static function instantiate($record){
			// could check record array
			// simple long form apprach
			// $object 			= new self();
			$class_name = get_called_class();
			$object = new $class_name;
			// $object->id 		= $record['id'];
			// $object->username 	= $record['username'];
			// $object->password 	= $record['password'];
			// $object->first_name = $record['first_name'];
			// $object->last_name 	= $record['last_name'];

			// more dynamic, short form approach
			foreach($record as $attribute=>$value){
				if($object->has_attribute($attribute))
					$object->$attribute = $value;
			}

			return $object;
		}

		private function has_attribute($attribute){
			// get_object vars returns an associative array with all attributes, incl. private ones, as the keys and their current values as the value

			$object_vars = get_object_vars($this);
			// we just need to know weather the key exists, true or false
			return array_key_exists($attribute, $object_vars);
		}

		protected function attributes() {
			// return an array of attribute keys and values
			// return get_object_vars($this);
			$attributes = array();
			foreach(static::$db_fields as $field){
				if(property_exists($this, $field)) {
					$attributes[$field] = $this->$field;
				}
			}

			return $attributes;
		}

		protected function sanitized_attributes() {
			global $database;
			$clean_attributes = array();

			foreach($this->attributes() as $key => $value){
				$clean_attributes[$key] = $database->escape_value($value);
			}
			return $clean_attributes;
		}

		public function save() {
			// a new record won't have an id yet
			return isset($this->id) ? $this->update() : $this->create();
		}

		public function create(){
			global $database;
			$attributes = $this->sanitized_attributes();
			// array_unshift(array_values($attributes), $database->insert_id());
			$attributes['id'] = $database->insert_id();
			$sql = "INSERT INTO " . static::$table_name . " (";
			$sql .= join(", ", array_keys($attributes));
			$sql .= ") VALUES ('";
			$sql .= join("', '", array_values($attributes));
			$sql .= "') ";

			if($database->query($sql)) {
				$this->id = $database->insert_id();
				return true;
			} else {
				return false;
			}
		}

		public function update(){
			global $database;

			$attributes = $this->sanitized_attributes();
			$attribute_pairs = array();
			foreach($attributes as $key => $value){
				$attribute_pairs[] = "{$key}='{$value}'";
			}

			$sql = "UPDATE " . static::$table_name . " SET ";
			$sql .= join(", ", $attribute_pairs);
			$sql .= " WHERE id=" . $database->escape_value($this->id);
			$database->query($sql);
			return ($database->affected_rows() == 1) ? true : false;
		}


		// public function create(){
		// 	global $database;

		// 	$object_vars = get_object_vars($this);

		// 	$keys = '(';
		// 	$values = ' (';

		// 	$c = count($object_vars);
		// 	$i = 0;

		// 	// echo "count(object_vars): " . count($object_vars) . "<hr>";

		// 	foreach($object_vars as $attr=>$val){
		// 		if($attr == 'id')
		// 			continue;
		// 		$keys .= $attr;
		// 		$keys .= ($i == count($object_vars) - 2) ? ') ' : ', ';
		// 		$values .= "'" . $database->escape_value($val) . "'";
		// 		$values .= ($i == count($object_vars) - 2) ? ') ' : ', ';
		// 		$i++;

		// 		// echo "attr: {$attr} => val: {$val}<br>";
		// 	}

		// 	// echo "keys: {$keys}<hr>";
		// 	// echo "values: {$values}<hr>";

		// 	// die();

		// 	$sql = 'INSERT INTO ' . static::$table_name . $keys . 'VALUES' . $values;
 
		// 	// die($sql);

		// 	if($database->query($sql)){
		// 		$this->id = $database->insert_id($database);
		// 		return true;
		// 	} else {
		// 		return false;
		// 	}
		// }

		// public function update(){
		// 	global $database;

		// 	$object_vars = get_object_vars($this);

		// 	$keys = '';
		// 	$values = '';
		// 	$sets = '';
		// 	$i = 0;

		// 	foreach($object_vars as $attr=>$val){
		// 		$sets .= $attr . "='" . $database->escape_value($val);
		// 		$sets .= ($i == count($object_vars) - 1) ? "' " : "', ";
		// 		$i++;
		// 	}

		// 	// die($sets);

		// 	$sql = 'UPDATE ' . static::$table_name . ' SET ' . $sets . "WHERE id={$this->id}";

		// 	// die($sql);

		// 	$database->query($sql);

		// 	return ($database->affected_rows() == 1) ? true : false;
		// }

		public function delete(){
			global $database;

			$sql = "DELETE FROM " . static::$table_name . " WHERE id={$this->id} LIMIT 1";

			$database->query($sql);

			return ($database->affected_rows() == 1) ? true : false;
		}


	}

	// }

?>