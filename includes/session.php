<?php 

	class Session {

		private $logged_in = false;
		public $user_id;
		public $message;

		function __construct(){
			session_start();
			$this->check_message();
			$this->check_login();
			if($this->logged_in) { 
				//logged in actions
			} else {
				// not logged in actions
			}
		}

		private function check_login() {
			if(isset($_SESSION['user_id'])){
				$this->user_id = $_SESSION['user_id'];
				$this->logged_in = true;
			} else {
				unset($this->user_id);
				$this->logged_in = false;
			}
		}

		public function is_logged_in(){
			return $this->logged_in;
		}

		public function login($user){
			// db check user/pass
			if($user){
				$this->user_id = $_SESSION['user_id'] = $user->id;
				$this->logged_in = true;
			}
		}

		public function logout(){
			unset($_SESSION['user_id']);
			unset($this->user_id);
			$this->logged_in = false;
		}

		private function check_message(){
			// is there a message stored in the session?
			if(isset($_SESSION['message'])){
				// add it as an attribute and erase the stored message
				$this->message = $_SESSION['message'];
				unset($_SESSION['message']);
			} else {
				$this->message = "";
			}
		}

		public function message($msg=""){
			if(!empty($msg)){
				// then this is "set message"
				$_SESSION['message'] = $msg;
			} else {
				// then this is "get message"
				return $this->message;
			}
		}

	}

	$session = new Session();
	$message = $session->message();

?>