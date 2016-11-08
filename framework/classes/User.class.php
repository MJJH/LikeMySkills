<?php
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
	public function __construct($id, $username, $email, $role, $permissions) {
		$this->id = $id;
		
		if(!empty($username) && isSet($username) && $this->validateUsername($username))
			$this->username = $username;
		else return;
		
		if(!empty($email) && isSet($email) && $this->validateEmail($email))
			$this->email = $email;
		else return;
		
		if(!empty($role) && isSet($role))
			$this->role = $role;
		else $this->role = "user";
		
		if(!empty($permissions) && isSet($permissions) && is_array($permissions))
			$this->permissions = $permissions;
		else $this->permissions = array();
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
		global $_queries;
		global $_database;
		
		// Validate input
		if(User::validateUsername($username) && User::validateEmail($email) && User::validatePassword($password)) {
			$_database->doQuery($_queries["adduser"], "sss", array(&$username, &$password, &$email));
		}
	}
	
	/**=
		* Load a user from the database using an Id
		* @param integer 	$id			Identifier to search in database
		* @return User		 			user by this id or false
	*/
	static public function loadUser($id) {
		
	}
	
	/**
		* Check if authentication with username and password
		*
		* @param username		User username
		* @param password		User password
		* @return User or false
	*/
	static public function signInUser($username, $password) {
		
	}
	
	/**
		Encrypt password string
		@param password		Input password to encryptPassword
		@return encrypted password string
	*/
	static private function encryptPassword($password) {
		return md5($password);
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
}