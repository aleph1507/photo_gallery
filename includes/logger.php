<?php 

	class Logger{

		private static function checkfiles(){
			if(!file_exists(LOG_FOLDER))
				mkdir(LOG_FOLDER);
		}

		public static function log_action($action, $message=""){
		// 2009-01-01 13:10:03 | Login: kskoglud logged in
		// photo_gallery/public/admin/logfile.php reads the logfile, link to clear log file, "logfile.php?clear=true", logs log clearing
			// die(LOG_FILE);
			self::checkfiles();
			if($log_handle = fopen(LOG_FILE, 'a')){
				// die("in log_handle");
				$timestamp = strftime("%Y-%m-%d %H:%M:%S", time() + 3600);
				// die("\n{$timestamp} | {$action}: {$message}");
				fwrite($log_handle, "\n{$timestamp} | {$action}: {$message}");
				fclose($log_handle);
			} else {
				if($log_handle = fopen(LOG_FILE, 'w')){
					// die("log_handle w");
					$timestamp = strftime("%Y-%m-%d %H:%M:%S", time() + 3600);
					fwrite($log_handle, "\n{$timestamp} | {$action}: {$message}");
					fclose($log_handle);
				}
			}
		}

		public static function clear_log(){
			global $session;
			if($log_handle = fopen(LOG_FILE, 'w')){
				$timestamp = strftime("%Y-%m-%d %H:%M:%S", time() + 3600);
				$user = User::find_by_id($session->user_id)->username;
				fwrite($log_handle, "{$timestamp} | Log cleared by {$user} with id: {$session->user_id}");
				fclose($log_handle);
			}

			redirect_to('logfile.php');
		}

		public static function read_log(){
			if($log_handle = fopen(LOG_FILE, 'r')){
				$logs = file_get_contents(LOG_FILE);
				fclose($log_handle);

				return $logs;
			}
		}
	}

?>