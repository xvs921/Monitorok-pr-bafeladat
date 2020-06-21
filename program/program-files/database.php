<?php
class Database
{
    public $servername = "localhost";
    public $username = "root";
    public $password = "";
    public $dbname = "warehouse";
	public $conn = NULL;
	public $sql = NULL;
	public $result = NULL;
	public $row = NULL;
	
	public function __construct()
	{
		$this->conn = new mysqli($this->servername, $this->username, $this->password);
		if ($this->conn->connect_error)
		{
			die ("Connection failed: " . $this->conn->connect_error);
		}
		$this->conn->query("SET NAMES 'UTF8';");
	}

	public function __destruct()
	{
		$this->conn->close();
    }
}?>