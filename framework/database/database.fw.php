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
		
		__construct() {
			// Get information from settings
			
			// Connect
			$this->mysqli = mysqli_connect($this->host, $this->username, $this->password, $this->database, $this->port);
			
			if($this->mysqli->connect_errno) {
				if($_settings["debug"]) 
					die $this->mysqli->connect_error;
				
				else return $_ERROR['connection'];
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