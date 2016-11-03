<?php
/** 
	Database connection
*/
	class Database {
		
		// Database info
		protected $host;
		protected $port;
		protected $username;
		protected $password;
		
		protected $database;
		
		// Connection
		protected $mysqli;
		
		function __construct() {
			global $_settings;
			// Get information from settings
			$this->host 	= $_settings["dbHost"] 		?: "localhost";
			$this->username = $_settings["dbUsername"] 	?: "root";
			$this->password = $_settings["dbPassword"] 	?: "";
			
			$this->database = $_settings["dbName"]		?: "database";
			
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