<?php namespace Addons\MailHandler;

// Require Mail
require_once "Mail.php";

class MailHandler {
	private $host;
	private $email;
	private $username;
	private $password;
	private $port;

	public function __construct() {
		global $util;
		
		$this->host 	= $util->getSetting("mailHost");
		$this->email 	= $util->getSetting("mailMail");
		$this->username = $util->getSetting("mailUser");
		$this->password = $util->getSetting("mailPass");
		$this->port 	= $util->getSetting("mailPort");
	}	
	
	public function send($to, $subject, $body) {
		$headers = array(
			'From' 			=> $this->email,
			'To' 			=> $to,
			'Subject' 		=> $subject,
			'MIME-Version'	=> '1.0',
			'Content-Type'  => 'text/html; charset=iso-8859-1; charset=UTF-8'
		);
		
		$smtp = \Mail::factory('smtp', array(
			'host' 			=> $this->host,
			'port' 			=> $this->port,
			'auth' 			=> true,
			'username' 		=> $this->username,
			'password' 		=> $this->password
		));
		
		$mail = $smtp->send($to, $headers, "<html><body>{$body}</body></html>");
		
		return $mail;
	}
}