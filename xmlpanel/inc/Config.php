<?php

header("Content-Type: text/html; charset=UTF-8");

$MySQLConfig = Array();
$MySQLConfig["Server"] = "localhost";
$MySQLConfig["UserName"] = "root";
$MySQLConfig["Password"] = "mygentr";
$MySQLConfig["Database"] = "ggxml";

define("MODEL_PATH", dirname(__FILE__)."/Class/Model");

class SQLConnect {
	private $_MySQL;

	function __construct(){
		$this->_MySQL = $GLOBALS["MySQLConfig"];

		if(count($this->_MySQL) < 1) die("MySQL parameters not found. 'Register Globals' may not work.");

		$NeedDatas = Array("Server", "UserName", "Password", "Database");
		foreach($NeedDatas as $Key){
			if(!isset($this->_MySQL[$Key]) || strlen($this->_MySQL[$Key]) < 1){
				die("MySQL parameters invaild.");
			}
		}
		
		mysql_connect($this->_MySQL["Server"], $this->_MySQL["UserName"], $this->_MySQL["Password"]) or die("Could not connect to MySQL.");
		mysql_select_db($this->_MySQL["Database"]) or die("Could not find database.");
		mysql_query("SET NAMES 'utf8'");
	}

	public function getSupplierDir($ID){
		$Query = mysql_query("SELECT dirname FROM tedarikci WHERE id = '". mysql_real_escape_string($ID) ."'");
		if(mysql_num_rows($Query) > 0){
			$Data = mysql_fetch_array($Query);
			return $Data["dirname"];
		} else  {
			return false;
		}
	}
}

class Tools {
	public function clear($Text){
		return mysql_real_escape_string($Text);
	}
	public function MergeArray($Array){
		if(!is_array($Array) && !is_object($Array)){
			$Array = Array();
		}
		return json_encode($Array);
	}
	public function Covert2Array($Text){
		$Data = json_decode($Text, true);

		return (array) $Data;
	}
}

?>