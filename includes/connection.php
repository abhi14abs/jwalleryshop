<?php

class DbConnect
{
	var $host = "localhost";
	var $user = "root";
	var $password = "";
	var $database = "bbjewels";
	var $persistent = false;
	var $conn;
	var $error_reporting = false;

	/* constructor */
	function __construct()
	{
		// Optional: You can auto-open connection here if you want
		// $this->open();
	}

	function open()
	{
		/* Connect to the MySQL Server and Select DB */
		$this->conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);

		if (!$this->conn) {
			return false;
		}

		return true;
	}

	/* close the connection */
	function close()
	{
		return mysqli_close($this->conn);
	}

	/* report error if error_reporting set to true */
	function error()
	{
		if ($this->error_reporting) {
			return mysqli_error($this->conn);
		}
	}
}

/* Class to perform query */
class DbQuery extends DbConnect
{
	var $result = '';
	var $sql;

	function __construct($sql1)
	{
		$this->sql = $sql1;
		// Ensure connection is open
		if (!$this->conn) {
			$this->open();
		}
	}

	function query()
	{
		return $this->result = mysqli_query($this->conn, $this->sql);
	}

	function affectedrows()
	{
		return mysqli_affected_rows($this->conn);
	}

	function numrows()
	{
		return mysqli_num_rows($this->result);
	}

	function fetchobject()
	{
		return mysqli_fetch_object($this->result);
	}

	function fetcharray()
	{
		return mysqli_fetch_array($this->result, MYSQLI_ASSOC);
	}

	function fetchassoc()
	{
		return mysqli_fetch_assoc($this->result);
	}

	function freeresult()
	{
		return mysqli_free_result($this->result);
	}
}
