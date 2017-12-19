<?php 

	require_once(LIB_PATH.DS.'database.php');

	class Photograph extends DatabaseObject {

		protected static $table_name="photographs";
		protected static $db_fields = ['id', 'filename', 'type', 'size', 'caption'];

		public $id;
		public $filename;
		public $type;
		public $size;
		public $caption;
 
 		private $temp_path;
 		protected $upload_dir = 'images';
 		public $errors = array();
	
 		protected $upload_errors = [
 			UPLOAD_ERR_OK => 'No errors.',
 			UPLOAD_ERR_INI_SIZE => 'Larger than form MAX_FILE_SIZE',
 			UPLOAD_ERR_FORM_SIZE => 'Larger than form MAX_FILE_SIZE',
 			UPLOAD_ERR_PARTIAL => 'Partial upload.',
 			UPLOAD_ERR_NO_FILE => 'No file',
 			UPLOAD_ERR_NO_TMP_DIR => 'No temporary directory',
 			UPLOAD_ERR_CANT_WRITE => 'Can\'t write to disk',
 			UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
 		];

 		// pass in $_FILE(['uploaded_file']) as an argument
 		public function attach_file($file) {
 			// error checking

 			if(!$file || empty($file) || !is_array($file)){
 				// error: nothing uploaded or wrong args
 				$this->errors[] = 'No file was uploaded.';
 				return false;
 			} elseif($file['error'] != 0)  {
 				// error: php defined error
 				$this->errors[] = $this->upload_errors[$file['error']];
 				return false;
 			} else{
	 			// set object attributes
	 			
	 			$this->temp_path = $file['tmp_name'];
	 			$this->filename = basename($file['name']);
	 			$this->type = $file['type'];
	 			$this->size = $file['size'];

	 			return true;
 			}
 			// no db saving yet
 		}

 		//overwrite the save() from db object
 		public function save(){
 			// a new record won't have an id yet
 			if(isset($this->id)){
 				// just to update caption
 				$this->update();
 			} else {
 				// error check
 				if(!empty($this->errors))
 					return false;

 				if(strlen($this->caption) >= 255){
 					$this->errors[] = 'The caption can only be 255 characters long';
 					return false;
 				}

 				// can't save without filename and temp location
 				if(empty($this->filename) || empty($this->temp_path)){
 					$this->errors[] = 'The file location was not available.';
 					return false;
 				}

 				//Determine target path

 				$target_path = SITE_ROOT . DS . 'public' . DS . $this->upload_dir . DS . $this->filename;


 				// check if already exists

 				if(file_exists($target_path)){
 					$this->errors[] = "The file {$this->filename} already exists.";
 					return false;
 				}

 				//attempt to move file

 				if(move_uploaded_file($this->temp_path, $target_path)){
 					//success
 					//save a corresponding entry to db
 					if($this->create()){
 						// no file at temp_path anymore
 						unset($this->temp_path);
 						return true;
 					}
 				} else {
 					// failure
 					$this->errors[] = 'The file upload failed, possibly due to incorrect permissions on the upload folder';
 					return false;
 				}

 				
 			}
 		}

 		public function image_path() {
 			return $this->upload_dir.DS.$this->filename;
 		}

 		public function size_as_text(){
 			if($this->size < 1024){
 				return "{$this->size} bytes";
 			} elseif($this->size < 1048576) {
 				$size_kb = round($this->size/1024);
 				return "{$size_kb} KB";
 			} else {
 				$size_mb = round($this->size/1048576, 1);
 				return "{$size_mb} MB";
 			}
 		}

		public function comments(){
			return Comment::find_comments_on($this->id);
		} 		

 		public function destroy(){
 			// remove db entry
 			if($this->delete()){
	 			// remove file
 				$target_path = SITE_ROOT.DS.'public'.DS.$this->image_path();
 				return unlink($target_path) ? true : false;
 			} else {
 				// db delete failed
 				return false;
 			}

 		}
 
	}
?>