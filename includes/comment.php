<?php 
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	// require_once('../vendor/phpmailer/phpmailer/src/PHPMailer.php');
	require_once(LIB_PATH.DS.'database.php');

	class Comment extends DatabaseObject {
		protected static $table_name="comments";
		protected static $db_fields = [
			'id', 'photograph_id', 'created', 'author', 'body'
		];

		public $id;
		public $photograph_id;
		public $created;
		public $author;
		public $body;

		public static function make($photo_id, $author="Anonymous", $body=""){
			if(!empty($photo_id) && !empty($author) && !empty($body)){
				$comment = new Comment();
				$comment->photograph_id = (int)$photo_id;
				$comment->created = strftime("%Y-%m-%d %H:%M:%S", time());
				$comment->author = $author;
				$comment->body = $body;
				return $comment;
			} else {
				return false;
			}
		}

		public static function find_comments_on($photo_id = 0){
			global $database;
			$sql = "SELECT * FROM " . static::$table_name;
			$sql .= " WHERE photograph_id=" . $database->escape_value($photo_id);
			$sql .= " ORDER BY created ASC";
			return self::find_by_sql($sql);
		}

		public function try_to_send_notification(){
			$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
			try {
			    //Server settings
			    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
			    $mail->isSMTP();                                      // Set mailer to use SMTP
			    $mail->Host = 'smtp.mail.yahoo.com';  // Specify main and backup SMTP servers
			    $mail->SMTPAuth = true;                               // Enable SMTP authentication
			    $mail->Username = 'cyberdawn2002@yahoo.com';                 // SMTP username
			    $mail->Password = 'Crackkme1234';                           // SMTP password
			    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			    $mail->Port = 587;                                    // TCP port to connect to

			    //Recipients
			    $mail->setFrom('cyberdawn2002@yahoo.com', 'Photo Gallery');
			    $mail->addAddress('cyberdawn2002@yahoo.com', 'Photo Galery testuser');     // Add a recipient
			    // $mail->addAddress('ellen@example.com');               // Name is optional
			    // $mail->addReplyTo('info@example.com', 'Information');
			    // $mail->addCC('cc@example.com');
			    // $mail->addBCC('bcc@example.com');

			    //Attachments
			    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

			    //Content
			    $mail->isHTML(true);                                  // Set email format to HTML
			    $mail->Subject = 'A new Photo Gallery Comment';
			    $mail->Body =<<<EMAILBODY

A new comment has been recieved in the Photo Gallery.

At {$this->created}, {$this->author} wrote:

{$this->body};
EMAILBODY;
			    $mail->AltBody = 'A new Photo Gallery Comment';

			    $mail->send();
			    echo 'Message has been sent';
			} catch (Exception $e) {
			    echo 'Message could not be sent.';
			    echo 'Mailer Error: ' . $mail->ErrorInfo;
			}
		}
	}

?>