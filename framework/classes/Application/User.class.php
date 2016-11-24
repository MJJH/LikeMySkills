<?php namespace Application;
/**
	An user is an object that can do actions on the application
*/
class User {
	// Identiifier of the user in the database
	private $id;
	
	// The name the user gave to be named by
	private $username;
	// Email adress that is bound to this user, to contact 
	private $email;
	
	// The name of the role this user has
	private $role;
	// An array of all permissions this user has.
	private $permissions;
	
	/**
		* Constructor of a user object
		* Able to do actions and show information
		*
		* @param integer	$id				identifier
		* @param string		$username		name for User
		* @param string		$email			contact email adress for user
		* @param string		$role			name of the role this user has
		* @param string[]	$permissions	array of all allowed permissions for this user
	*/
	public function __construct($id, $username, $email, $role = "default", $permissions = array()) {
		$this->id = $id;
		
		$this->username = $username;
		
		$this->email = $email;
		
		global $util;
		$this->role = ($role === "default" ? $util->getSetting("defaultRole") : $role);
		
		$this->permissions = $permissions;
	}
	
	public function hasPermission($permission) {
		return in_array($permission, $this->permissions);
	}
	
	static public function getCookieHash($username) {
		global $util;
		
		$date = date('l jS \of F Y h:i:s A');
		return crypt($username."-".$date, '$6$rounds=5000$'.$util->getSetting("cookieHash").'$');
	}
	
	/**
		* Register a user in the database
		*
		* @author	Martijn
		* @param string 	$username		Username the user wants to use
		* @param string 	$password		Password the user wants to use
		* @param string		$email			Email adress of the user, to send information
	*/
	static public function register($username, $password, $email) {
		global $util;
		// Validate input
		if(User::validateUsername($username) && User::validateEmail($email) && User::validatePassword($password) && User::uniqueUsername($username) && User::uniqueEmail($email)) {
			$encryptedPass = User::encryptPassword($password);
			
			$util->getDatabase()->doQuery($util->getQuery("adduser"), "sss", array(&$username, &$encryptedPass, &$email));
		}
	}
	
	/**=
		* Load a user from the database using an Id
		* @param integer 	$id			Identifier to search in database
		* @return User		 			user by this id or false
	*/
	static public function loadUser($util, $id) {
		$userRow = $util->getDatabase()->getQuery($util->getQuery("loadUser"), "i", array(&$id));
		
		if($userRow) {
			return new User($userRow['userid'], $userRow['username'], $userRow['email'], $userRow['role'], $util->getPermissions($userRow['role']));
		}
		return null;
	}
	
	/**
		* Check if authentication with username and password
		*
		* @param username		User username
		* @param password		User password
		* @return User or false
	*/
	static public function signInUser($username, $password) {
		global $util;
		
		// Check if login
		if(User::validateUsername($username)) {
			$encryptedPass = User::encryptPassword($password);
			
			$users = $util->getDatabase()->getQuery($util->getQuery("findUser"), "ss", array(&$username, &$encryptedPass));
		
			if($users) {
				$cookie = User::getCookieHash($username);
				$device = $_SERVER['HTTP_USER_AGENT'];
				$ip = ip2long($_SERVER['REMOTE_ADDR']);
				$id = $users['userid'];
				$util->getDatabase()->doQuery($util->getQuery("addCookie"), "ssis", array(&$id, &$device, &$ip, &$cookie));
				setcookie("userLogin", $cookie);
			} else {
				$util->addError("noLogin");
			}
		}
	}
	
	/**
		Encrypt password string
		@param password		Input password to encryptPassword
		@return encrypted password string
	*/
	static private function encryptPassword($password) {
		global $util;
		return crypt($password, '$6$rounds=5000$'.$util->getSetting("loginHash").'$');
	}
	
	/**
		Validate the username
		@param string	username 	To validate username
		@return boolean		True if valid username
	*/
	static public function validateUsername($username) {
		return preg_match("/^[a-zA-Z0-9._-]{4,30}$/", $username);
	}
	
	/**
		Check unique username
		@param string 	username	check username
		@return boolean		True if email is unique
	*/
	static public function uniqueUsername($username) {
		global $util;
		
		$output = $util->getDatabase()->getQuery($util->getQuery("uniqueName"), "s", array(&$username));
		
		return $output['name'] <= 0;
	}
	
	/**
		Validate the password
		@param string	password 	To validate password
		@return boolean		True if valid password
	*/
	static public function validatePassword($password) {
		return true;
	}
	
	/**
		Validate the email
		@param string 	email 		To validate email
		@return boolean		True if valid email
	*/
	static public function validateEmail($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	
	/** 
		Check unique email
		@param string	email		Check email
		@return boolean		True if email is unique
	*/
	static public function uniqueEmail($email) {
		global $util;
		
		$output = $util->getDatabase()->getQuery($util->getQuery("uniqueEmail"), "s", array(&$email));
		return $output['mail'] <= 0;
	}
	
	// Setters & Getters
	/**
		* Get ID from this object
		*
		* @author Martijn
		* @return id	Return ID from this object
	*/
	public function getId() {
		return $this->id;
	}
	
	public function getUsername() {
		return $this->username;
	}
}