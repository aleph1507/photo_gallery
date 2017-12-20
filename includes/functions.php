<?php 

	function strip_zeros_from_date($marked_string = ""){
		$no_zeros = str_replace('*0', '', $marked_string);
		$cleaned_string = str_replace('*','',$no_zeros);
		return $cleaned_string;
	}

	function redirect_to($location = NULL){
		if($location != NULL){
			header("Location: {$location}");
			exit;
		}
	}

	function output_message($message=""){
		if(!empty($message)){
			return "<p class=\"message\">{$message}</p>";
		} else {
			return "";
		}
	}

	function __autoload($class_name) {
		$class_name = strtolower($class_name);
		$path = LIB_PATH.DS."{$class_name}.php";
		$mailpath = PHPMAILER_PATH.DS."{$class_name}.php";
		if(file_exists($path)){
			require_once($path);
		} elseif(file_exists($mailpath)) {
			require_once($mailpath);
		} else {
			die("The file {$class_name}.php could not be found");
		}
	}

	function include_layout_template($template=""){
		include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
	}

	function log_action($action, $message=""){
		// 2009-01-01 13:10:03 | Login: kskoglud logged in
		// photo_gallery/public/admin/logfile.php reads the logfile, link to clear log file, "logfile.php?clear=true", logs log clearing
	}

	function datetime_to_text($datetime=""){
		$unixdatetime = strtotime($datetime);
		return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
	}

?>