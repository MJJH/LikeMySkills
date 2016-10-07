<?php
/** 
	Database connection
*/
	class Database {
		
		// Database info
		$host;
		$port;
		$username;
		$password;
		
		$database;
		
		// Connection
		$mysqli;
		
		__construct($connection) {
			// Get information from settings
			$this->host 	= $connection["host"] 		|| "localhost";
			$this->port 	= $connection["port"] 		|| 21;
			$this->username = $connection["username"] 	|| "root";
			$this->password = $connection["password"] 	|| "";
			
			$this->database = $connection["database"]	|| "database";
			
			// Connect
			$this->mysqli = mysqli_connect($this->host, $this->username, $this->password, $this->database, $this->port);
			
			// If error, stop and display
			if($this->mysqli->connect_errno) {
				if($_settings["debug"]) 
					die $this->mysqli->connect_error;
				
				else return $_ERROR['connection'];
			}
			
			// If tabels don't exist, reset
			if($result = $this->mysqli->query("SHOW TABLES LIKE 'settings'")) {
				if($result->num_rows <= 0) {
					$this->reset();
				}
			}
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
		function doQuery($key, $params) {
			
		}
		
		/**
			Run a GET QUERY
			- SELECT
			
			Query parameters will be escaped
			
			Returns array object
		*/
		function getQuery($key, $params) {
			
		}
	}