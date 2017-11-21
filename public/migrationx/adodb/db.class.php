<?php 
//include ("adodb.inc.php");
class myconn{
	var $host="localhost";
	var $dbuser="root";
	var $dbpasswd="shahalam";
	
	function myconn(){
		/*$db = ADONewConnection("mysql");
		print_r($db);exit;
		$this->conn = $db->Connect($this->host,$this->dbuser,$this->dbpasswd,$this->dbname);
		print_r($this->conn);*/
	}
}
?>