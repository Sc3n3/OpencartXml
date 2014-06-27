<?php

class UserDataModel {
	public $_Details;
	
	public function __construct(){
		$this->_Details["Data"] = Array();
	}
	
	public function debug(){
		echo "<pre>";
		print_r($this->_Details);
		echo "</pre>";
	}
	
	//------------------------------------------------
	
	public function setID($ID){
		$this->_Details["ID"] = (integer) $ID;
		return $this;
	}
	
	public function setUserID($ID){
		$this->_Details["UserID"] = (integer) $ID;
		return $this;
	}
	
	public function setSuppID($ID){
		$this->_Details["SuppID"] = (integer) $ID;
		return $this;		
	}
	
	public function setName($Name){
		$this->_Details["Name"] = $Name;
		return $this;
	}
	
	public function setData($Name, $Value){
		$this->_Details["Data"][$Name] = $Value;
		return $this;
	}
	
	//------------------------------------------------

	public function getID(){
		return $this->_Details["ID"];
	}
	
	public function getUserID(){
		return $this->_Details["UserID"];
	}
	
	public function getSuppID(){
		return $this->_Details["SuppID"];	
	}
	
	public function getName(){
		return $this->_Details["Name"];
	}
	
	public function getData($Name){
		return $this->_Details["Data"][$Name];
	}
	
	//------------------------------------------------

	public function getUserAllData(){
		$Tool = new Tools();
		
		$SQL   = "SELECT * FROM kullanici WHERE tedarikci_id = '". $Tool->clear($this->_Details["SuppID"]) ."'";
		$Query = mysql_query($SQL);
		
		if(mysql_num_rows($Query) > 0){
			$Data = mysql_fetch_assoc($Query);
			$this->setID($Data["id"]);
			$this->setUserID($Data["user_id"]);
			$this->setSuppID($Data["tedarikci_id"]);
			$this->setName($Data["name"]);
			$this->_Details["Data"] = $Tool->Covert2Array($Data["data"]);
						
			return true;
		} else {
			return false;
		}
	}
	
}

?>