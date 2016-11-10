<?php namespace Essentials;
/**
 *  Database Class
 *  
 *  Handles connection and all input.
 *  Uses prepared statements to secure all data
 *  
 *  @author Martijn Vriens
 *  
 *  @version 0.1
 *  
 *  @copyright 2016
 *  
 *  @package Essentials
 */
	class Database {
		
		// Database info
		/**
		 *  IP or URL to connect to
		 *  
		 *  @var	string
		 */
		protected $host;
		/**
		 *  Username of the connecting user
		 *  
		 *  @var	string	$username
		 */
		protected $username;
		/**
		 *  Password of the connecting user
		 *  @var	string	@password
		 */
		protected $password;
		/**
		 *  Database name for this connection
		 *  @var	string	$database
		 */
		protected $database;
		
		/**
		 *  Mysqli Object connection
		 *  
		 *  @link http://php.net/manual/en/book.mysqli.php
		 *  
		 *  @var	mysqli	$mysqli
		 */
		protected $mysqli;
		
		function __construct($util) {
			// Get information from settings
			$this->host 	= $util->getSetting("dbHost") 		?: "localhost";
			$this->username = $util->getSetting("dbUsername") 	?: "root";
			$this->password = $util->getSetting("dbPassword") 	?: "";
			
			$this->database = $util->getSetting("dbName")		?: "database";
			
			// Connect
			$this->mysqli = mysqli_connect($this->host, $this->username, $this->password, $this->database);
			
			// If error, stop and display
			if($this->mysqli->connect_errno) {
				if($_settings["debug"]) 
					die ($this->mysqli->connect_error);
				
				else return $_ERROR['connection'];
			}
			
			// If tabels don't exist, reset
			/*if($result = $this->mysqli->query("SHOW TABLES LIKE 'settings'")) {
				if($result->num_rows <= 0) {
					$this->reset();
				}
			}*/
		}
		
		/**
			Delete all tables and remake them using the create.sql
			- Only run if create.sql exists and is not empty
			
			Returns true if database is reset
		*/
		function reset() {
			// Reset all tables from create.sql
			
			return false;
		}
		
		/**
			Run a DO QUERY
			- INSERT, UPDATE, CREATE, DELETE, DROP
			
			Query parameters will be escaped
			
			Returns amount of rows changed
		*/
		function doQuery($query, $types, $params) {
			$affected = 0;

			// Check if connection is open
			if($this->mysqli->ping()) {
				
				// Prepare query
				if($stmt = $this->mysqli->prepare($query)) {
				
					// Bind values
					call_user_func_array(array($stmt, "bind_param"), array_merge(array(&$types), $params));
					
					$stmt->execute();
					
					if($stmt->affected_rows < 0) {
						echo $stmt->error;
					}
					
					$affected = $stmt->affected_rows;
					
					$stmt->close();
				}
				
			}
			
			return $affected;
		}
		
		/**
			Run a GET QUERY
			- SELECT
			
			Query parameters will be escaped
			
			Returns array object
		*/
		function getQuery($query, $types, $params) {
			
		}
	}