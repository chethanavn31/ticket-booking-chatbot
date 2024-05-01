<?php
class DBController {
//private $host = "192.168.1.233";
    private $host = "localhost";
	private $server_user = "root";
	private $server_password = "Nitya@123";
	private $server_database = "bib_DB";
	private $server_connection;
	
	function __construct() {
		$this->conn = $this->connectDB();
	}
	
	function connectDB() {
		$server_connection = mysqli_connect($this->host,$this->server_user,$this->server_password,$this->server_database);
		return $server_connection;
	}
	
	function runQuery($query) {
		$result = mysqli_query($this->conn,$query);
		while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
		}		
		if(!empty($resultset))
                {
			return $resultset;
                }
	}
	
	function numRows($query) {
		$result  = mysqli_query($this->conn,$query);
		$rowcount = mysqli_num_rows($result);
		return $rowcount;	
	}
}
?>
